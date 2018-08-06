<?php
class Personmodel extends CI_Model {

    /**
     * название текущей таблицы. По дефолту должно быть, person.
     * @var string
     */
private $t = 'person'; //название текущей таблицы, что бы менять в одном месте

//Объявляем массивы с перечислениями
public $e_sex = array(1 => 'мужчина', 2 => 'женщина');
public $e_sexual_orientation = array(1 => 'гетеро', 2 => 'лесби', 3=> 'бисексуал', 4=>'пансексуал');
public $e_relationship = array(1 => 'свободен', 2 => 'в отношениях', 3=> 'в гражданском браке', 4=>'в браке', 5=>'разведен');
public $e_education = array(1 => 'среднее',2=>'срднее-специальное', 3 => 'высшее', 4=> 'нескольких высших',
    5=>'ученая степень', 6=>'иностранное образование');
public $e_income = array(1 => 'нет дохода',2=>'небольшой доход', 3 => 'средний доход', 4=> 'высокий доход',
        5=>'непостоянные заработки'); //TODO: добавить строку income в базу данных
public $e_employment = array(1 => 'безработный',2=>'непостоянные заработки', 3 => 'фрилансер', 4=> 'предприниматель',
        6=>'живу на доход от чего то', 7=>'непостоянные заработки', 8=>'стабильная работа', 9=>'руководитель');
public $e_smoke = array(1 => 'заядлый курильщик', 2 => 'курю иногда', 3 => 'курю когда выпью',
        4 => 'изредка балуюсь', 5 => 'не курю', 6 => 'Никогда не пробовал курить');
public $e_alcohol = array(1 => 'пью регулярно', 2 => 'пью за компанию (социально)',3 => 'пью изредко',
    4 => 'почти не пью',5 => 'противник алкоголя',5 => 'никогда не пробовал алкоголь', 6 =>'не пью');
public $e_sport = array(1 => 'Профессиональный спортсмен',  2 => 'Мастер спорта', 3 =>'Занимаю регулярно',
    4 => 'Иногда знимаюсь', 5 => 'Изредка занимаюсь', 6 => 'Не занимаюсь');
public $e_health = array(1 => 'есть проблемы со здоровьем', 2 => 'небольшие проблемы со здоровьем', 3 => 'в целом все нормально',
    4 => 'здоров', 5 => 'отличное здоровье');
public $e_virus_hiv = array(1 => 'Носитель', 2 => 'Нет');
public $e_virus_hepatitis_c = array(1 => 'Носитель', 2 => 'Нет');

//Как я вижу зачатия и дальнейшие отношения
//TODO: Сделать таблички для этого
public $e_sposob_inception = array(1 => 'По договоренности', 2 => 'Натурально', 3 => 'ИИ в доманшних условиях',
    4 => 'ИИ в клинике', 5 => 'ЭКО или ИКСИ');
public $e_dalneshee_ibshenie = array(1 => 'Не имеет значения', 2 => 'Общение по желанию', 3 => 'Удаленное общение (переписка)',
    4 => 'Общение по выходным (папа выходного дня)', 5 => 'Постоянное общение', 6 => 'Совместное проживание',
    7 => 'Создание полноценной семьи', 8 => 'Общение по желанию матери', 9 => 'Общение по желанию ребенка');

public $e_oplata_partneru = array(1 => 'Без оплаты', 2 => 'Оплата затрат (анализы и т.д.)' , 3 => 'Оплата проезда' ,
    4 => 'По договоренности' , 5 => 'Оплату помощь донора считаю желательной');


//TODO: добавить нужные строки в базу данных потом.


    function __construct()
{
    parent::__construct();
}


public function insert_person(int $id, int $height, int $weight, int $children, int $sex, int $sexual_orientation,
int $relationship, int $education, int $employment, int $smoke, int $alcohol, int $sport, int $health, int $virus_hiv,
int $virus_hepatitis_c)

{
//Массив который будет вставлен в таблицу
$data = array(
'id' => $id,
'height'=> $height,
'weight'=> $weight,
'children'=> $children,
'sex'=> $sex,
'sexual_orientation'=> $sexual_orientation,
'relationship'=> $relationship,
'education'=> $education,
'employment'=> $employment,
'smoke'=> $smoke,
'alcohol'=> $alcohol,
'sport'=> $sport,
'health'=> $health,
'virus_hiv'=> $virus_hiv,
'virus_hepatitis_c'=> $virus_hepatitis_c,
);
$this->db->insert($this->t, $data);
}

    public function get_int()
    {
             return $this->glob_var;
    }

    public function get_int_with_increment()
    {
      SELF::$test_int++;
      return ++$this->glob_var;
    }




//
////Вставляет новую группу пользователей
//public function insert_user_group($group_name,$group_description)
//{
//$data = array(
//'groups_name' => $group_name,
//'groups_description'=> $group_description,
//);
//$this->db->insert(SELF::$this_user_groups, $data);
//return $this->db->insert_id();
//}
//
//
////Раздел сессий
//
////Функция которая вставляет новую сессиию
////Когда пользователь входит с опцией "Remember Me"
//public function insert_user_session($random_selector,$sha256_validator,$user_id)
//{
//$data = array(
//'users_sessions_selector' => $random_selector,
//'users_sessions_validator' => $sha256_validator,
//'users_sessions_user_id' => $user_id,
//);
//$this->db->insert(SELF::$users_sessions_table_name, $data);
//}
//
//
////Удаляет конкретную запись из сессии
////с конкретной кукой
//public function delete_one_user_session($selector)
//{
//  $this->db->where('users_sessions_selector',$selector);
//  $this->db->limit(1);
//  $this->db->delete(SELF::$users_sessions_table_name);
//}
//
//
////Функция для того что бы исключь коллизию с одинаковым селектором
////Не нашли - возвращаем FALSE
////Нашли - возвращаем TRUE
//public function find_collision_selecto_session($selector)
//{
//$this->db->where('users_sessions_selector',$selector);
//$result_int = $this->db->count_all_results(SELF::$users_sessions_table_name);
//return ($result_int <= 0) ? FALSE : TRUE;
//}
//
////Проверка правильности куки
////находим куку по $user_selector
////Проводим проверку хэша sha256  $user_validator (в базе хранится только хэщ для защиты от возможных утечек)
////Если вдруг такое случится что валидатор не походит (что скорее всего попытка взлома)
////запись из базы (а куку убираем в котроллере, ибо так более MVC)
//public function check_cookie_and_return_id($user_selector,$user_validator)
//{
//$this->db->where('users_sessions_selector',$user_selector);
//$this->db->limit(1);
//$query = $this->db->get(SELF::$users_sessions_table_name);
////Проверяем нашли ли мы строку вообще
//if ($query->num_rows() > 0)
//{
//$ses = $query->row_array();
//
//  if(hash_equals(hash('sha256', $user_validator),$ses['users_sessions_validator']))
//  {
//    return $ses['users_sessions_user_id'];
//  }
//  //Если вдруг по какой то причине (что скорее всего попытка взлома) валадиатор не подошел удаляем запись с текущим селектором и возвращаем false
//  else {
//     $this->delete_one_user_session($user_selector);
//     return false;
//    }
//}
//else
//{return false; // Не нашли строку (нет такого селектора)
//}
//}//Конец функции
//
//
//
////Удаляет все записи которые старше двух месяцев
//function delete_old_sessions()
//{
//$this->db->query("DELETE FROM ". SELF::$users_sessions_table_name ." WHERE users_sessions_timestamp < NOW() - INTERVAL 2 MONTH ");
//}
//
//function delete_old_sessions_test()
//{
//$this->db->query("DELETE FROM ". SELF::$users_sessions_table_name ." WHERE users_sessions_timestamp < NOW() - INTERVAL 2 SECOND; ");
//}
//
//
//
//
////Раздел пользователей
//
////Вставляем данные о последнеё попытке захода
//public function insert_login_attempts(int $id,bool $increment)
//{
//    $this->db->where('id', $id);
//    $this->db->set('last_time_wrong_pass_unix_tsmp', time());
//
//    if ($increment === TRUE)
//    {
//    $this->db->set('tries_with_wrong_password', 'tries_with_wrong_password+1', FALSE);
//    }
//    else {$this->db->set('tries_with_wrong_password', 1);}
//
//    $this->db->limit(1);
//    $this->db->update(SELF::$this_table_name);
//}
//
//
//public function set_zero_login_attempts_with_wrong_password(int $id)
//    {
//        $this->db->where('id', $id);
//        //$this->db->set('last_time_wrong_pass_unix_tsmp', 0);
//        $this->db->set('tries_with_wrong_password', 0);
//        $this->db->limit(1);
//        $this->db->update(SELF::$this_table_name);
//    }
//
//
//
//    /**Обновляет поле blocked_up_to_date в базе, для того, что бы заблокировать пользователя из-за
//	 * большого колличества попыток войти с неправильным паролем
//     * @param int $id (id пользователя)
//     * @param int $minutes (до какого времени заблокировать) Unix time stamp в int
//     */
//public function block_user_on_x_minutes(int $id, int $minutes)
//{
//   // $date = new DateTime();
//  //  $date->modify("+{$minutes} minutes");
//
//    $data = array(
//        'blocked_up_to_date'=> $minutes
//    );
//
//    $this->db->where('id', $id);
//    $this->db->set('tries_with_wrong_password', 0);
//    $this->db->limit(1);
//    $this->db->update(SELF::$this_table_name, $data);
//
//}
//
//
//
//public function count_login_attempts_and_delte_old_entries(int $userid): int
//{
//$deleted_rows=0;
//$tries=0;
////Удаляем старые записи из таблицы
////Хотя скорее всего лучше это дело перенести в отдельный скрипт который будет раз
////в одну минуту запускаться
////Считаем колличенство попыток входа и удаляем старые
////TODO:Убрать удаление в отдельный файл которй будет запускатсья регулярно
//// $query = $this->db->query('delete FROM '. SELF::$users_login_attemts .
////' WHERE login_attempts_time < NOW() - INTERVAL 1 MINUTE;');
////$deleted_rows =  $this->db->affected_rows();
////Делаем запрос на колличество попыток входа
//
//$date = new DateTime('-10 minutes');
//
//$this->db->where('login_attempts_user_id',$userid);
//$this->db->where('login_attempts_time >=',
//	$this->db->escape($date->format('Y-m-d H:i:s'))
//	,FALSE);
//$tries = $this->db->count_all_results(SELF::$users_login_attemts);
//return $tries;
//}
//
//
//
////Функция обновляет последнюю активность пользователя на сайте
//public function update_user_last_activity($id)
//{
//$date = new DateTime();
//
//$data = array(
//'user_last_active_date'=>date("Y-m-d H:i:s")
//);
//$this->db->where('id', $id);
//$this->db->limit(1);
//$this->db->update(SELF::$this_table_name, $data);
//return date("Y-m-d H:i:s");
//}
//
//
//    /**
//     * Вставляет пользователя
//     * Возвращает ID вставленное в таблицу
//     * @param string $user_name
//     * @param string $user_email
//     * @param string $password
//     * @param bool $activate_by_mail
//     * @return int
//     */
//    public function insert_user_registration(string $user_name, string $user_email, string $password, bool $activate_by_mail=TRUE):int
//        {
//         $date = new DateTime();
//         $date->getTimestamp();
//
//         $user_is_active = $activate_by_mail===TRUE?0:1;
//
//         $data = array(
//        'user_name' => $user_name,
//        'user_email'=> $user_email,
//        'password'=> password_hash($password,PASSWORD_DEFAULT),
//        'isactivated'=>$user_is_active,
//        'group_id'=> $this->config->item('my_conf_default_user_group'),
//        'user_registration_ip'=> getenv('REMOTE_ADDR'),
//        'user_registration_ip_if_proxy'=> getenv('HTTP_X_FORWARDED_FOR'),
//        'user_last_active_date'=>   date("Y-m-d H:i:s"),
//        'user_registration_date'=>   date_format($date,"Y-m-d H:i:s"),
//        );
//        $this->db->insert(SELF::$this_table_name, $data);
//        return $this->db->insert_id();
//        }
//
//
//
//         public function insert_user_registration_with_any_user_group($user_name,$user_email,$password,$user_group_id)
//        {
//        		//$site_users_obj = new site_users_class;
//        		//$site_users_obj->user_name = $user_name;
//        		//$site_users_obj->user_email = $user_email;
//        		//$site_users_obj->password = $password;
//        	    //$this->db->insert('site_users', $site_users_obj);
//        	   $date = new DateTime();
//			   $date->getTimestamp();
//
//        	     $data = array(
//        'user_name' => $user_name,
//        'user_email'=> $user_email,
//        'password'=> password_hash($password,PASSWORD_DEFAULT),
//        'group_id'=> $user_group_id,
//        'user_registration_ip'=> getenv('REMOTE_ADDR'),
//        'user_registration_ip_if_proxy'=> getenv('HTTP_X_FORWARDED_FOR'),
//        'user_last_active_date'=>   date("Y-m-d H:i:s"),
//        'user_registration_date'=>   date_format($date,"Y-m-d H:i:s"),
//
//        );
//        $this->db->insert(SELF::$this_table_name, $data);
//        return $this->db->insert_id();
//
//        }
//
//        public function find_user_whith_pass_exist_and_return_all_data($user_name_or_password,$password)
//        {
//
//			$this->db->where('user_name',$user_name_or_password);
//			$this->db->or_where('user_email',$user_name_or_password);
//			$query = $this->db->get('site_users');
//
//
//			if ($query->num_rows() > 0)
//			{
//				$pass = $query->row()->password;
//				if(password_verify($password,$pass))
//				{
//				return $query->row();
//				} else
//				{
//					return FALSE;
//				}
//
//			} else
//			{
//				return FALSE;
//			}
//
//		}
//
//
//
////Функция найти пользователя по имени (для опредлеения дупликатов)
//public function if_user_exist_by_name(string $name):bool
//{
//$this->db->where('user_name',$name);
//$result_int = $this->db->count_all_results(SELF::$this_table_name);
//return ($result_int == 0) ? FALSE : TRUE;
//}
//
////Функция найти пользователя email (для опредлеения дупликатов)
//public function if_user_exist_by_email(string $email):bool
//{
//$this->db->where('user_email',$email);
//$result_int = $this->db->count_all_results(SELF::$this_table_name);
//return ($result_int == 0) ? FALSE : TRUE;
//}
//
//
//
//        public function find_user_whith_pass_exist_true_or_false($user_name_or_password,$password)
//        {
//
//			$this->db->where('user_name',$user_name_or_password);
//			$this->db->or_where('user_email',$user_name_or_password);
//			$query = $this->db->get('site_users');
//
//
//			if ($query->num_rows() > 0)
//			{
//				$pass = $query->row()->password;
//				if(password_verify($password,$pass))
//				{
//				return TRUE;
//				} else
//				{
//					return FALSE;
//				}
//
//			} else
//			{
//				return FALSE;
//			}
//
//		}
//
//
//    /**
//	 * Function looks for a match by using username or email (both must be unique)
//	 * Return true or false
//	 * 	 *
//     * @param string $user_name_or_email
//     * @return bool
//	 *
//     */
//    public function find_user_exist(string $user_name_or_email): bool        {
//        	$this->db->where('user_name',$user_name_or_email);
//			$this->db->or_where('user_email',$user_name_or_email);
//			$num_ret = $this->db->count_all_results('site_users');
//			return $num_ret > 0 ? TRUE : FALSE; //Больше нуля возвращаем TRUE
//		}
//
//
//    /**
//	 * Function looks for a match by using ID
//	 * Return true or false
//	 *
//     * @param int $id  UserID - только инты
//     * @return bool User is found or not
//     */
//    public function find_user_exist_by_id(int $id): bool        {
//        	$this->db->where('id',$id);
//			$num_ret = $this->db->count_all_results('site_users');
//			return $num_ret > 0 ? TRUE : FALSE; //Больше нуля возвращаем TRUE
//		}
//
//
//    /**
//	 * Ищет пользователя по логину или емайлу. Использует оптимизацию индексов для избегания конструкции OR
//	 *
//	 * Возвращает FALSE если не нашел пользователя.
//	 *
//	 * Если все ОК - Возвращает массив с одной записью из таблицы site_users
//	 *
//     * @param string $user_name_or_email  Логин или Email пользователя
//     * @return bool|array
//     */
//public function find_user_exist_and_return_user_data(string $user_name_or_email)
//	{
////Ескейпаем строку, для обеспечения безопасности от SQL injection
//$escp_str = $this->db->escape($user_name_or_email);
////Запрос с оптимизацией, для использования индеса. Должны быть индексы на полях user_name и user_email
//$query = $this->db->query('SELECT * FROM `site_users` WHERE `user_name` ='.$escp_str.'
//UNION
//SELECT * FROM `site_users` WHERE `user_email` = '.$escp_str);
//
//if ($query->num_rows() > 0)
//{
//	return  $query->row_array();
//} else
//{
//	return FALSE;
//}
//	}
//
//
//public function insert_user_activation_code(int $id, string $code)
//{
//$data = array(
//'user_activation_code' => $code,
//'user_that_will_be_activated_id'=> $id,
//);
//$this->db->insert('users_activation_code', $data);
//}
//
//public function delete_activation_code(int $id)
//{
//$this->db->where('user_that_will_be_activated_id',$id)->limit(1);
//$this->db->delete('users_activation_code');
//}
//
//public function get_activation_code_by_code(string $code)
//{
//$this->db->where('user_activation_code',$code);
//$query = $this->db->get('users_activation_code');
//return $query->num_rows()>0 ? $query->row_array() : FALSE;
//}
//
//public function get_activation_code_by_id(int $id)
//{
//$this->db->where('user_that_will_be_activated_id',$id);
//$query = $this->db->get('users_activation_code');
//return $query->num_rows()>0 ? $query->row_array() : FALSE;
//}
//
//public function update_activation_code(int $id, string $code): int
//{
//    $this->db->where('user_that_will_be_activated_id',$id);
//    $this->db->set('user_activation_code',$code);
//    $this->db->update('users_activation_code');
//    return $this->db->affected_rows();
//}
//
//public function activate_user(int $id)
//{
//    $this->db->where('id',$id)->limit(1);
//    $this->db->set('isactivated',1);
//    $this->db->update('site_users');
//}
//
//
//
//    public function find_user_exist_and_return_user_data_by_id($userid)
//        {
//      $this->db->where('id',$userid);
//      $this->db->limit(1);
//			$query = $this->db->get('site_users');
//
//			if ($query->num_rows() > 0)
//			{
//				return  $query->row_array();
//			} else
//			{
//				return FALSE;
//			}
//
//		}
//
//        public function get_first_user()
//        {
//		 $this->db->where('user_name',"glorsh");
//		 $query = $this->db->get('site_users');
//		 $row = $query->row();
//
//			if (isset($row))
//			{
//			$out['user_name']= $row->user_name;
//			$out['id']= $row->id;
//			$out['user_email']= $row->user_email;
//            return $out;
//			}
//		}
//
//        public function get_last_ten_entries()
//        {
//                $query = $this->db->get('entries', 10);
//                return $query->result();
//
//        }
//
//        public function insert_entry()
//        {
//                $this->title    = $_POST['title']; // please read the below note
//                $this->content  = $_POST['content'];
//                $this->date     = time();
//
//                $this->db->insert('entries', $this);
//        }
//
//        public function update_entry()
//        {
//               $data = array(
//        'user_name' => 'arrayname',
//        'user_email'=>''
//       );
//                //$site_users_obj = new site_users_class;
//        		//$site_users_obj->user_name = 'test_name';
//        		//$site_users_obj->user_email = $user_email;
//        		//$site_users_obj->password = $password;
//
//				$this->db->where('id', 4);
//				$this->db->update('site_users', $data);
//
//
//        }

}


?>
