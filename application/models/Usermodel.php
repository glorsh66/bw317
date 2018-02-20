<?php


class Usermodel extends CI_Model {

      //Статические
    private static $this_table_name = 'site_users'; //название текущей таблицы, что бы менять в одном месте
    private static $users_sessions_table_name = 'users_sessions'; //Название таблицы сессий юзеров
    private static $users_login_attemts = 'login_attempts'; //Попытки захода пользователйе
    private static $this_user_groups = 'user_groups';
    private static $test_int = 1;
    public $glob_var=0;

    function __construct()
    {
        parent::__construct();
    //    $this->glob_var=0;
      //  SELF::$test_int++;
      $this->glob_var++;
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





//раздел группы пользователей

//Вставляет новую группу пользователей
public function insert_user_group($group_name,$group_description)
{
$data = array(
'groups_name' => $group_name,
'groups_description'=> $group_description,
);
$this->db->insert(SELF::$this_user_groups, $data);
return $this->db->insert_id();
}


//Раздел сессий

//Функция которая вставляет новую сессиию
//Когда пользователь входит с опцией "Remember Me"
public function insert_user_session($random_selector,$sha256_validator,$user_id)
{
$data = array(
'users_sessions_selector' => $random_selector,
'users_sessions_validator' => $sha256_validator,
'users_sessions_user_id' => $user_id,
);
$this->db->insert(SELF::$users_sessions_table_name, $data);
}


//Удаляет конкретную запись из сессии
//с конкретной кукой
public function delete_one_user_session($selector)
{
  $this->db->where('users_sessions_selector',$selector);
  $this->db->limit(1);
  $this->db->delete(SELF::$users_sessions_table_name);
}


//Функция для того что бы исключь коллизию с одинаковым селектором
//Не нашли - возвращаем FALSE
//Нашли - возвращаем TRUE
public function find_collision_selecto_session($selector)
{
$this->db->where('users_sessions_selector',$selector);
$result_int = $this->db->count_all_results(SELF::$users_sessions_table_name);
return ($result_int <= 0) ? FALSE : TRUE;
}

//Проверка правильности куки
//находим куку по $user_selector
//Проводим проверку хэша sha256  $user_validator (в базе хранится только хэщ для защиты от возможных утечек)
//Если вдруг такое случится что валидатор не походит (что скорее всего попытка взлома)
//запись из базы (а куку убираем в котроллере, ибо так более MVC)
public function check_cookie_and_return_id($user_selector,$user_validator)
{
$this->db->where('users_sessions_selector',$user_selector);
$this->db->limit(1);
$query = $this->db->get(SELF::$users_sessions_table_name);
//Проверяем нашли ли мы строку вообще
if ($query->num_rows() > 0)
{
$ses = $query->row_array();

  if(hash_equals(hash('sha256', $user_validator),$ses['users_sessions_validator']))
  {
    return $ses['users_sessions_user_id'];
  }
  //Если вдруг по какой то причине (что скорее всего попытка взлома) валадиатор не подошел удаляем запись с текущим селектором и возвращаем false
  else {
    $this->db->where('users_sessions_selector',$user_selector);
    $this->db->limit(1);
    $this->db->delete(SELF::$users_sessions_table_name);
      return false;
    }
}
else
{return false; // Не нашли строку (нет такого селектора)
}
}//Конец функции



//Удаляет все записи которые старше двух месяцев
function delete_old_sessions()
{
$this->db->query("DELETE FROM ". SELF::$users_sessions_table_name ." WHERE users_sessions_timestamp < NOW() - INTERVAL 2 MONTH ");
}

function delete_old_sessions_test()
{
$this->db->query("DELETE FROM ". SELF::$users_sessions_table_name ." WHERE users_sessions_timestamp < NOW() - INTERVAL 2 SECOND; ");
}




//Раздел пользователей

//Вставляем данные о последнеё попытке захода
public function insert_login_attempts($id,$ip)
{
$data = array(
'ip_address' => $ip,
'login_attempts_user_id'=> $id,
);
$this->db->insert(SELF::$users_login_attemts, $data);
}


//Считаем колличенство попыток входа и удаляем старые
//TODO:Убрать удаление в отдельный файл которй будет запускатсья регулярно
public function count_login_attempts_and_delte_old_entries($userid)
{
$deleted_rows=0;
$tries=0;
//Удаляем старые записи из таблицы
//Хотя скорее всего лучше это дело перенести в отдельный скрипт который будет раз
//в одну минуту запускаться
 $query = $this->db->query('delete FROM '. SELF::$users_login_attemts .
' WHERE login_attempts_time < NOW() - INTERVAL 1 MINUTE;');
$deleted_rows =  $this->db->affected_rows();


  //Делаем запрос на колличество попыток входа
  $this->db->where('login_attempts_user_id',$userid);
  $tries = $this->db->count_all_results(SELF::$users_login_attemts);
  return array($deleted_rows,$tries);
}



//Функция обновляет последнюю активность пользователя на сайте
public function update_user_last_activity($id)
{
$date = new DateTime();
$date->getTimestamp();

$data = array(
'user_last_active_date'=>date("Y-m-d H:i:s")
);
$this->db->where('id', $id);
$this->db->limit(1);
$this->db->update(SELF::$this_table_name, $data);
return date("Y-m-d H:i:s");
}







	   		//Вставляем пользователя после регистрации
	   		//В качестве ответа получаем Id вставленной строки

	    public function insert_user_registration_form($user_name,$user_email,$password)
        {
        		//$site_users_obj = new site_users_class;
        		//$site_users_obj->user_name = $user_name;
        		//$site_users_obj->user_email = $user_email;
        		//$site_users_obj->password = $password;
        	    //$this->db->insert('site_users', $site_users_obj);
         $date = new DateTime();
			   $date->getTimestamp();

        	     $data = array(
        'user_name' => $user_name,
        'user_email'=> $user_email,
        'password'=> password_hash($password,PASSWORD_DEFAULT),
        'group_id'=> $this->config->item('my_conf_default_user_group'),
        'user_registration_ip'=> getenv('REMOTE_ADDR'),
        'user_registration_ip_if_proxy'=> getenv('HTTP_X_FORWARDED_FOR'),
        'user_last_active_date'=>   date("Y-m-d H:i:s"),
        'user_registration_date'=>   date_format($date,"Y-m-d H:i:s"),
        );
        $this->db->insert(SELF::$this_table_name, $data);
        return $this->db->insert_id();

        }



         public function insert_user_registration_form_with_id($user_name,$user_email,$password,$user_group_id)
        {
        		//$site_users_obj = new site_users_class;
        		//$site_users_obj->user_name = $user_name;
        		//$site_users_obj->user_email = $user_email;
        		//$site_users_obj->password = $password;
        	    //$this->db->insert('site_users', $site_users_obj);
        	   $date = new DateTime();
			   $date->getTimestamp();

        	     $data = array(
        'user_name' => $user_name,
        'user_email'=> $user_email,
        'password'=> password_hash($password,PASSWORD_DEFAULT),
        'group_id'=> $user_group_id,
        'user_registration_ip'=> getenv('REMOTE_ADDR'),
        'user_registration_ip_if_proxy'=> getenv('HTTP_X_FORWARDED_FOR'),
        'user_last_active_date'=>   date("Y-m-d H:i:s"),
        'user_registration_date'=>   date_format($date,"Y-m-d H:i:s"),

        );
        $this->db->insert(SELF::$this_table_name, $data);
        return $this->db->insert_id();

        }

        public function find_user_whith_pass_exist_and_return_all_data($user_name_or_password,$password)
        {

			$this->db->where('user_name',$user_name_or_password);
			$this->db->or_where('user_email',$user_name_or_password);
			$query = $this->db->get('site_users');


			if ($query->num_rows() > 0)
			{
				$pass = $query->row()->password;
				if(password_verify($password,$pass))
				{
				return $query->row();
				} else
				{
					return FALSE;
				}

			} else
			{
				return FALSE;
			}

		}




        public function find_user_whith_pass_exist_true_or_false($user_name_or_password,$password)
        {

			$this->db->where('user_name',$user_name_or_password);
			$this->db->or_where('user_email',$user_name_or_password);
			$query = $this->db->get('site_users');


			if ($query->num_rows() > 0)
			{
				$pass = $query->row()->password;
				if(password_verify($password,$pass))
				{
				return TRUE;
				} else
				{
					return FALSE;
				}

			} else
			{
				return FALSE;
			}

		}


        public function find_user_exist($user_name_or_email)
        {
        	$this->db->where('user_name',$user_name_or_email);
			$this->db->or_where('user_email',$user_name_or_email);
			$query = $this->db->get('site_users');

			if ($query->num_rows() > 0)
			{
				return TRUE;

			} else
			{
				return FALSE;
			}

		}


    //Функция - находит пользователя
    //И возвращает только одну строку данных пользователя
    public function find_user_exist_and_return_user_data($user_name_or_email)
        {
      $this->db->where('user_name',$user_name_or_email);
			$this->db->or_where('user_email',$user_name_or_email);
			$query = $this->db->get('site_users');

			if ($query->num_rows() > 0)
			{
				return  $query->row_array();
			} else
			{
				return FALSE;
			}

		}



    public function find_user_exist_and_return_user_data_by_id($userid)
        {
      $this->db->where('id',$userid);
      $this->db->limit(1);
			$query = $this->db->get('site_users');

			if ($query->num_rows() > 0)
			{
				return  $query->row_array();
			} else
			{
				return FALSE;
			}

		}








        public function ret_true()
        {
			return TRUE;
		}


        public function get_first_user_as_class()
        {
			 $this->db->where('user_name',"glorsh");
		     $query = $this->db->get('site_users');
			 $row = $query->row(0, 'Usermodel');

			 return $row;

		}


		   public function get_all_user_as_class()
        {
			 $query = $this->db->get('site_users', 10);
             return  $query->custom_result_object(self::$this_table_name);

		}


        public function get_first_user()
        {
		 $this->db->where('user_name',"glorsh");
		 $query = $this->db->get('site_users');
		 $row = $query->row();


			if (isset($row))
			{
			$out['user_name']= $row->user_name;
			$out['id']= $row->id;
			$out['user_email']= $row->user_email;
            return $out;
			}


		}











        public function get_last_ten_entries()
        {
                $query = $this->db->get('entries', 10);
                return $query->result();

        }

        public function insert_entry()
        {
                $this->title    = $_POST['title']; // please read the below note
                $this->content  = $_POST['content'];
                $this->date     = time();

                $this->db->insert('entries', $this);
        }

        public function update_entry()
        {
               $data = array(
        'user_name' => 'arrayname',
        'user_email'=>''
       );
                //$site_users_obj = new site_users_class;
        		//$site_users_obj->user_name = 'test_name';
        		//$site_users_obj->user_email = $user_email;
        		//$site_users_obj->password = $password;

				$this->db->where('id', 4);
				$this->db->update('site_users', $data);


        }

}



?>
