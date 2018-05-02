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
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'pm_text' => $pm);

        //Начинаем транзакцию
        $this->db->trans_begin();
        $this->db->insert($this->pm_table,$data);
        $insertId = $this->db->insert_id();

        //Формируем запрос для вставки боарда
        $sql_q = 'INSERT INTO '.$this->board_table.' (lesser_id, greater_id, all_count,lesser_count,greater_count,lesser_unread,greater_unread,last_message)
        VALUES (?, ?, 1,'."$tolesser,$togreater,$tolesser,$togreater,".  $insertId.') ON DUPLICATE KEY UPDATE '.
        "all_count=all_count+1,lesser_count=lesser_count+$tolesser,greater_count=greater_count+$togreater,lesser_unread=lesser_unread+$tolesser,".
        "greater_unread=greater_unread+$togreater,last_message=$insertId;";
        $query = $this->db->query($sql_q,array($lesser,$greater));
        $this->db->trans_complete(); //закрыли транзакцию
    }


    /**
     * Функция возвращает борду пользователей
     * Возможно с лимитом на число выводимых строк и оффсетом (для пагинации)
     * На выходе возвращает либо массив строк, либо FALSE если у пользователя еще нет никаких активных переписок.
     * @param int $me_id
     * @param int $limit
     * @param int $offset
     * @return bool|array
     */
    public function get_board(int $me_id, int $limit=0, int $offset=0)
{
//Запрос для получения:
//Борды со всеми сообщениями
$sql='select pb.*, pm.from_id,pm.to_id,pm.PM_timestamp,pm.has_been_read,pm.pm_text,
su.user_name,su.user_last_active_date,su.isactivated
from '.$this->board_table.' as pb
left join '.$this->pm_table.' as pm on pb.last_message = pm.id
left join '.$this->pm_table.' as su on pm.from_id=su.id
where pb.lesser_id ='.$me_id.' or pb.greater_id='.$me_id.'order by pb.last_message DESC';
if ($limit  >0 && $offset > 0) $sql .="limit $limit offset $offset";
 //Выполняем запрос
$query =  $this->db->query($sql);
return $query->num_rows() > 0? $query->result_array():FALSE;
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
     * Функция возвращает всю переписку для конкретного пользователя, с другим пользователем.
     * Также отмечает сообщения как прочитанные
     * !!!Внимание - функция не берет из таблицы данные о другом пользователе. Т.к. мы и так знаем первого пользователя
     * (владельца письма) и нужно всего один раз взять информацию о втором пользователе, вместо того что бы делать LEFT
     * JOIN. Это нужно делать в контроллере или библиотеке
     * @param $owner_id
     * @param $second_user_id
     * @return array|bool
     */
    public function get_message_thread($owner_id, $second_user_id)
{
    $lesser = min($owner_id,$second_user_id);
    $greater = max($owner_id,$second_user_id);
    $query = $this->db->where('lesser_id',$lesser)->or_where('greater_id',$greater)->get($this->pm_table);
    return $query->num_rows() > 0? $query->result_array():FALSE;
}





}
?>
