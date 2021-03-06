<?php
class PMmodel extends CI_Model {

private $pm_table ='private_messages';
private $board_table = 'PM_board';
private $users_table = 'site_users';

    /**
     * Вставляет сообщение в базу.
     * Увеличивает счетчик полученных/прочитанных сообщений.
     * Не осуществляет никаких проверок (только работа с базой данной).
     * Т.е. нужно обязательно проверять на наличие пользователя в блок листе.
     * А также нужно проводить антиспам проверку.
     * @param int $from
     * @param int $to
     * @param string $pm
     */
    public function insert_message(int $from, int $to, string $pm)
    {
        //Готовим данные
        $lesser = min($from,$to);
        $greater = max($from,$to);

        $tolesser =0;
        $togreater =0;
        //Чекаем где добавлять плюсик. Т.е. Кому добавлять полученных сообщений.
        if ($to === $lesser) $tolesser=1;
        else $togreater=1;

        //Данные для вставки в базу
        $data = array(
        'from_id' => $from,
        'to_id' => $to,
        'lesser_id' => $lesser,
        'greater_id' => $greater,
        'ip_address' => substr($_SERVER['REMOTE_ADDR'],0,50), //Обрезаем до длины строки
        'pm_text' => $pm);

        //Транзакции лучше не делать, а то может получиться деадлок
        $this->db->insert($this->pm_table,$data);
        $insertId = $this->db->insert_id();

        //Формируем запрос для вставки боарда
        $sql_q = 'INSERT INTO '.$this->board_table.' (lesser_id, greater_id, all_count,lesser_count,greater_count,lesser_unread,greater_unread,last_message,lesser_last_read,greater_last_read)
        VALUES (?, ?, 1,'."$tolesser,$togreater,$tolesser,$togreater,".  $insertId.',0,0) ON DUPLICATE KEY UPDATE '.
        "all_count=all_count+1,lesser_count=lesser_count+$tolesser,greater_count=greater_count+$togreater,lesser_unread=lesser_unread+$tolesser,".
        "greater_unread=greater_unread+$togreater,last_message=$insertId";
        $this->db->query($sql_q,array($lesser,$greater));
    }


    /**
     * Функция возвращает борду пользователей
     * Возможно с лимитом на число выводимых строк и оффсетом (для пагинации)
     * На выходе возвращает либо массив строк, либо FALSE если у пользователя еще нет никаких активных переписок.
     * Возвращает массив ['lesser_id'] ['greater_id'] ['all_count'] ['lesser_count'] ['greater_count'] ['lesser_unread']
     * ['greater_unread'] ['last_message'] ['from_id'] ['to_id'] ['PM_timestamp'] ['has_been_read'] ['pm_text']
     * ['user_name'] ['user_last_active_date'] ['isactivated']
     *
     * @param int $me_id
     * @param int $limit
     * @param int $offset
     * @return bool|array
     */
    public function get_board(int $me_id, int $limit=0, int $offset=0)
{
//Запрос для получения:
//Борды со всеми сообщениями

//Используем оптимизацию для UNION
$sql = "select pb.*,
pm.pm_text,
lu.user_name as lesser_user_name,
gu.user_name as greater_user_name
from (SELECT * FROM `PM_board` WHERE `lesser_id`={$me_id}
UNION
SELECT * FROM `PM_board` WHERE `greater_id` ={$me_id}) as pb
left join private_messages as pm  on pb.last_message=pm.id
left join site_users as lu on lu.id=pb.lesser_id
left join site_users as gu on gu.id=pb.greater_id
";


//Старый запрос
//$sql='select pb.*, pm.from_id,pm.to_id,pm.PM_timestamp,pm.pm_text,
//su.user_name,su.user_last_active_date,su.isactivated
//from '.$this->board_table.' as pb
//left join '.$this->pm_table.' as pm on pb.last_message = pm.id
//left join '.$this->users_table.' as su on pm.from_id=su.id
//where pb.lesser_id ='.$me_id.' or pb.greater_id='.$me_id.' order by pb.last_message DESC ';

if ($limit  >0) $sql .="limit $limit offset $offset";
 //Выполняем запрос
$query =  $this->db->query($sql);
return $query->num_rows() > 0? $query->result_array():FALSE;
}


    /**
     * Функция возвращает всю переписку для конкретного пользователя, с другим пользователем.
     * Также отмечает сообщения как прочитанные (в борде, и все отдельные сообщения для конкретных пользователей)
     * Ворзвращает = $messages['messages'], ['board']
     * !!!Внимание - функция не берет из таблицы данные о другом пользователе. Т.к. мы и так знаем первого пользователя
     * (владельца письма) и нужно всего один раз взять информацию о втором пользователе, вместо того что бы делать LEFT
     * JOIN. Это нужно делать в контроллере или библиотеке
     * @param $owner_id
     * @param $second_user_id
     * @return array|bool
     */
    public function get_message_thread($owner_id, $second_user_id,int $limit=0, int $offset=0) //Ворзвращает = $messages['messages'], ['board']
{
    $lesser = min($owner_id,$second_user_id);
    $greater = max($owner_id,$second_user_id);

    //Временная переменная для отладки
    $messages['q'][]="";

    $owner_is_lesser = $owner_id===$lesser?TRUE:FALSE; // Определяем владелеца

    //Вначале ищем борду сообщений
    $query = $this->db->where('lesser_id',$lesser)->where('greater_id',$greater)
        ->limit(1)->get($this->board_table);

    $messages['q'][]= $this->db->last_query(); //временная для отладки

    if (!($query->num_rows() > 0)) return FALSE;// У нас нет такой борды. Значит и нет никаких сообщений между
    // пользователями. Закрываем функцию возвращаем FALSE

    //создаем переменную
    $messages['board'] =  $query->row_array();

//Берем все сообщения для пользователя
//Проводим оптимизацию для использования OFFSET иначе может сильно тупить
        if ($limit  >0 ) //Если у нас есть в запросе pagination
        {
$sql = "select * from (select id from {$this->pm_table} where lesser_id={$lesser} and greater_id={$greater} order by id desc
 limit {$limit} offset {$offset}) as t 
join {$this->pm_table} as pm on pm.id=t.id order by t.id desc"; //Используем оптимизацию
$query = $this->db->query($sql);

//Не эффективный метод При большом оффсете значительно дольше срабатывает.
//$query=$this->db->where('lesser_id',$lesser)->where('greater_id',$greater)
//->order_by('id','DESC')->limit($limit)->offset($offset)->get($this->pm_table);
        } else // иначе мы берем все записи без оффсета
        {
$query=$this->db->where('lesser_id',$lesser)->where('greater_id',$greater)
    ->order_by('id','DESC')->get($this->pm_table);
        }

        $messages['q'][]= $this->db->last_query(); //временная для отладки

    if (!($query->num_rows() > 0)) return FALSE;//Если по какой то причине (удалились сообщения, а в борде об этом нет
    //информации то выходим из функции
    $messages['messages'] = $query->result_array(); //Берем сообщения. В них еще указанно, прочитанно сообщение или нет.

    //Если нужно обнулять
    $number_of_not_readed = $owner_is_lesser?$messages['board']['lesser_unread']:$messages['board']['greater_unread'];
    if (((int)$number_of_not_readed)===0) return $messages; //Если непрочитанных сообщений 0, то просто возвращаем массив


    //Если нет то тогда нужно обнулить значения непрочитанных сообщений
    //Обнуляем непрочитанные в борде
    if ($owner_is_lesser)
    {
        $this->db->set('lesser_unread',0);
        $this->db->set('lesser_last_read','last_message',FALSE);
    }
    else
    {
        $this->db->set('greater_unread',0);
        $this->db->set('greater_last_read','last_message',FALSE);
    }

    $this->db->where('lesser_id',$lesser)->where('greater_id',$greater);
    $this->db->update($this->board_table);

    //TODO:Убрать отладочные запросы
    $messages['q'][]= $this->db->last_query(); //временная для отладки

    return $messages; //Ворзвращает = $messages['messages'], ['board']

}




    /**
     * Функция возвращает колличетсво текущих веток переписки для конкретного пользователя
     * возвращает просто значение в int
     * @param int $me_id
     * @return int
     */
    public function count_board(int $me_id): int
{
    return $this->db->where('lesser_id',$me_id)->or_where('greater_id',$me_id)
        ->count_all_result($this->board_table);
}

    /**
     * @param int $owner
     * @param int $second
     * @return int
     */
public function count_all_messages(int $owner, int $second): int
{
$lesser = min($owner,$second);
$greater = max($owner,$second);
return $this->db->where('lesser_id',$lesser)->where('greater_id',$greater)->count_all_results($this->pm_table);
}

public function count_ammount_of_sended_in_x_minutes(int $from, int $minutes):int
{
$date = new DateTime();
$date->modify("-{$minutes} minutes");
$date_str = $date->format('Y-m-d H:i:s');

return $this->db->where('from_id',$from)->where('PM_timestamp >',$date_str)->count_all_results($this->pm_table);
}


    /**
     * Функция обощает данные из таблицы PM_board (не пересчитывая их реально из таблицы private_messages)
     * Используется оптимизация запроса через union
     * Возвращает массив int ['unread']['messages']['all_messages']['threads']['unread_threads']
     * Или FALSE в случае если нет таких сообщений
     *
     * @param itn $owner
     * @return array|bool
     */
    public function count_all_new_messages_from_board(int $owner)
{
//Используеи UNIOUN оптимизацию, что бы оптимально использовать индексы.
$query = $this->db->query("SELECT * FROM `PM_board` WHERE `lesser_id` ={$owner}
UNION
SELECT * FROM `PM_board` WHERE `greater_id` = {$owner}");

if (!($query->num_rows() > 0)) return FALSE;

$res = $query->result_array();
$len = count($res);
if (!is_array($res) or !($len>0)) return false;

$sum_unread=0;
$sum_messages=0;
$sum_all_mess=0;
$sum_threads=$len;
$sum_threads_with_new=0;

for ($i=0;$i<$len;$i++)
{
$lesser = (int)$res[$i]['lesser_id'];
if ($lesser===$owner)
    {
        $sum_unread  +=(int)$res[$i]['lesser_unread'];
        $sum_messages+=(int)$res[$i]['lesser_count'];
        if ((int)$res[$i]['lesser_unread']>0) $sum_threads_with_new++;
    }
else {
       $sum_unread  +=(int)$res[$i]['greater_unread'];
       $sum_messages+=(int)$res[$i]['greater_count'];
       if ((int)$res[$i]['greater_unread']>0) $sum_threads_with_new++;
}
$sum_all_mess+=(int)$res[$i]['all_count'];

}
$ret = array();
$ret['unread']=$sum_unread;
$ret['messages']=$sum_messages;
$ret['all_messages']=$sum_all_mess;
$ret['threads']=$sum_threads;
$ret['unread_threads']=$sum_threads_with_new;

return $ret;
}




}
?>
