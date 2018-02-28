<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class simple_auth_lib {
  public $user_loggined = false;
  public $user_cause_of_error = "";
  private   $number_of_tries = 0;

  public $error = "";


  //основные переменные
  public $user_data = null;
  public $is_user_loggined = FALSE;


  //Переменные для заполнения ошибками (когда пользователь заходит на сайт)
  //Ошибки - user_login_from_form
  public $error_user_name_or_email_form_is_empty = null; //Возвращается если строка переданная дял юзера пустая
  public $error_password_from_form = null; // возвращается если пароль пустой
  public $error_limit_for_login_attemts_with_wrong_password_is_exceeded = null; //возвращается если колличество попыток захода к данному пользователю с неправильным пароле превысило максимальные допустимое значение
  public $error_password_is_wrong = null;
  public $error_user_is_not_found = null;

  //Переменные для теста
  public $already_logged = FALSE;
  public $logged_otkuda = "";

  //Переменные для класса
  const  max_tries_with_incorrect_password = 10;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
                $this->CI->load->helper('url');
                $this->CI->load->helper('cookie');
                $this->CI->load->model('Usermodel');
                $this->CI->load->library('session');

        }

//Функция которая работает когда пользователй ввел даные с формы
//возвращает boolean в зависимости от того успешно ли прошла авторизация или нет
//Если ошибка всетаки встречена то заполняет свойства объекта которые равны этому значению
public function user_login_from_form(string $user_name_or_email_form, string $password_from_form ,bool $remeber_me=true) : bool
{

  //Проверям данные которые пришли с формы
  //(по хорошему нужно проверять еще на длинну)
  if (empty($user_name_or_email_form))
  {
    $this->error_user_name_or_email_form_is_empty = "Username or email sting from form is empty";
    return false;
  }

  if (empty($password_from_form))
  {
    $this->error_password_from_form = "Password is empty";
    return false;
  }

  $this->user_data=   $this->CI->Usermodel->find_user_exist_and_return_user_data($user_name_or_email_form);
  //Проверям не вернулся ли фалс
  if ($this->user_data) {

  //Проверям сходится ли пароль
  //Смотрим сколько было попыток захода
  //Нужно потом переписать что бы не было удаления в данном скрипте
  $deleted_rows =0;
  $loging_attempts_with_wrong_password =0;

  //TODO: вынести удаление старых попыток в отдельный файл
  list($deleted_rows,$loging_attempts_with_wrong_password)= $this->CI->Usermodel->count_login_attempts_and_delte_old_entries($this->user_data['id']);


  //Проводим проверку на колличество попыток войти с неправильным паролем
  //Если колличество попыток превышенно то учетная запись блокируется на время
  if ($loging_attempts_with_wrong_password >= SELF::max_tries_with_incorrect_password)
  {
    $this->error_limit_for_login_attemts_with_wrong_password_is_exceeded = "You have tried to log in with a wrong password more that a " .
    SELF::max_tries_with_incorrect_password. " times. Have a rest and have two cups of tea.";
    return false;
  }


  if (password_verify($password_from_form,$this->user_data["password"])) {

    //Блок для того что бы запонмить пользователя в cookies
    //Если естевственно пользователь не хочет что бы стояла галочка запомнить то данные в куки заноситься не будут
    if ($remeber_me) {
    //Генерируем абсолютно случайный хэш
    //Мы храним - отдельно селектор для того, что бы исключить возможность обнаружения колличества зарегенных польхователей на сайте
    //Отдельной валидатор.
    //Открытый валидатор мы храним - у пользователя в куках
    //Хэш мы храним в базе. Это нужно для того, что в случае если база пользователей утечет - то злоумышленники не получат доступ ко всем пользователям

    //Do While - для того что бы исключить теоритеческий конфликт если будет сгенеренна одинаковый selector
    do
    {
      $random_selector = bin2hex(random_bytes(30));
    }
    while ($this->CI->Usermodel->find_collision_selecto_session($random_selector));
    $random_validator = bin2hex(random_bytes(100));
    $sha256_validator  = hash('sha256', $random_validator);


    //Вставляем сессию в базу данных
    //Мы можем быть уверенны так как она прошла валидацию и исключен факт одинаковых записей
    $this->CI->Usermodel->insert_user_session($random_selector,$sha256_validator, $this->user_data["id"]);

    //Орпеделяем параметры куки Параметры куки
    $cookie = array(
      'name'   => 'user_selector',
      'value'  => $random_selector,
      'expire' => '5184000'); //Колличество секунд в двух месяцах. В одном - 2592000
      $this->CI->input->set_cookie($cookie);

    $cookie = array(
      'name'   => 'user_validator',
      'value'  => $random_validator,
      'expire' => '5184000'); //Колличество секунд в двух месяцах. В одном - 2592000
      $this->CI->input->set_cookie($cookie);
  }else //Окончание If remember me
  {
    //Удаляем куки на всякий случай если они были включены
    //Но не сработали по какой то причине
  if (!is_null(get_cookie('user_selector')))
  {
   $this->CI->Usermodel->delete_one_user_session(get_cookie('user_selector',TRUE));
  }
    delete_cookie('user_selector');
    delete_cookie('user_validator');
  }  //Конец блока if для remeber_me




    //создаем сессию (это мы делаем любом случае)
    $this->CI->session->set_userdata('user_logged_id', $this->user_data["id"]);
    $this->CI->session->set_userdata('user_logged_name', $this->user_data["user_name"]);
    $this->CI->session->set_userdata('user_logged_email', $this->user_data["user_email"]);
    $this->CI->session->set_userdata('user_logged_group_id', $this->user_data["group_id"]);
    $this->CI->session->set_userdata('user_remeber_me',$remeber_me); // Для того что бы в случае когда мы заходим по сессии не создавались куки



  //Удаляем старые записи (старше чем 2 месяца)
  //Хотя лучше всего это дело перенести в cron
  //TODO: перенести удаление старых сессий в cron
  $this->CI->Usermodel->delete_old_sessions();
  $this->is_user_loggined = true; //Пользователь успешно зашел значит данные точно есть в переменной
  return true; // Возвразаем true пользователь зашел.

  }// конец иф, если не верный пароль
  else
  {
  //Добавляем инфу о попытке захода пользователя
  $this->CI->Usermodel->insert_login_attempts($this->user_data["id"],$this->CI->input->ip_address());

  //Если
  if ($loging_attempts_with_wrong_password > 0 &&
  $loging_attempts_with_wrong_password <=SELF::max_tries_with_incorrect_password)
  {
    $this->error_password_is_wrong = "Password is wrong. And be aware - you only have "
    . (SELF::max_tries_with_incorrect_password-($loging_attempts_with_wrong_password)) . " attempts "
    ." from ". SELF::max_tries_with_incorrect_password  . " before your account will be blocked for a while. So check your password twice.";
    return false;
  }
  else
  {
    $this->error_password_is_wrong = "Password is wrong";
    return false;
  }

  }
  } //конец иф, если не найден пользователь
  else
  {
    $this->error_user_is_not_found = "User is not found";
    return false;
  }
  return false;
}//end of the function



//Функция которая проверят залогинен ли пользователь
//Вначале проверям переменную (данные пользователя), что бы не вызывать функцию по 10 раз
//Если функция пустая, то проверям вначале сессиию
//А потом уже проверям куки. (И если кукисы сработали, то мы пересоздаем сессиию, что бы с неё брать инфу)

//Данные о пользовтеле притаскивают из базы данных каждый раз (так как он может быть забанен)
public function check_if_user_is_loggined(): bool
{

//Если пользовтель уже залогинен
if ((!is_null($this->user_data) && ($this->user_loggined==true)))
{
$this->already_logged = TRUE;
return true;
}

  //Проверям существует ли сессия
  if($this->CI->session->userdata('user_logged_id'))
  {
  $user_id = $this->CI->session->userdata('user_logged_id');
  $this->logged_otkuda = 'Из сессии';
  }
  //Проверяем если ли две куки
  elseif(get_cookie('user_selector') && get_cookie('user_validator'))
  {
  $user_selector = get_cookie('user_selector',TRUE);
  $user_validator = get_cookie('user_validator',TRUE);
  $user_id = $this->CI->Usermodel->check_cookie_and_return_id($user_selector,$user_validator);
  $this->logged_otkuda = 'Из кукисов';

  //Если вернулся false удаляем текущие куки
  //Это могло быть по двум причинам
  //1) Запись удалена так как старая
  //2) Попытка взлома (в даном случае модель убирает запись из базы)
  if (!$user_id) {
    delete_cookie('user_validator');
    delete_cookie('user_selector');
    return false;
                 }
  }else {
    return FALSE; // Если не нашли не в куке, не в сессии
  }


  //Проверяем не false ли user_id из сессии или куки
  //Если нет значит пользователь не зарегестрирован
  if ($user_id)
  {
  $this->user_data = $this->_get_user_data_and_update_last_activity($user_id);
  //Существует ли такой пользователь
  //Если существует то мы обновим сессию на всякий случай
  if ($this->user_data)
  {
  $this->CI->session->set_userdata('user_logged_id', $this->user_data["id"]);
  $this->CI->session->set_userdata('user_logged_name', $this->user_data["user_name"]);
  $this->CI->session->set_userdata('user_logged_email', $this->user_data["user_email"]);
  $this->CI->session->set_userdata('user_logged_group_id', $this->user_data["group_id"]);
  return TRUE;
} else { //Если не вернул данных для польхователей из базы
  return FALSE;}
} else {return false;} //Не в сессии не в куках не нашлось USER_ID

return FALSE; // Если по какой то причине дошли до этой строки
}




//Функция для регистрации пользователя
//После успешной регистрации пользовтеля отправляем письмо
public function register($user_name,$user_email,$password):bool
{
//Грубая проверка данных что бы не допустить значений которые база не может принять
//Проверям пустые ли строки
if (empty($user_name)){$this->error = "User_name is empty"; return FALSE;}
if (empty($user_email)){$this->error = "Email is empty"; return FALSE;}
if (empty($password)){$this->error = "Password is empty"; return FALSE;}

//Проверяем длинну строк
if (strlen($user_name)>255){$this->error = "User_name is more than 255 characters"; return FALSE;}
if (strlen($user_email)>255){$this->error = "Email is more than 255 characters"; return FALSE;}
if (strlen($password)>255){$this->error = "Password is more than 255 characters"; return FALSE;}

//Проверям правильность email
if (filter_var($user_email, FILTER_VALIDATE_EMAIL)=== false){$this->error = "Sender adress is invalid email adress"; return FALSE;}


//TODO: Добавить проверку уникальности введеного имени и email


//Грубая проверка данных что бы не допустить значений которые база не может принять
//Проверям пустые ли строки
if (empty($user_name)){$this->error = "User_name is empty"; return FALSE;}
if (empty($user_email)){$this->error = "Email is empty"; return FALSE;}
if (empty($password)){$this->error = "Password is empty"; return FALSE;}

//Проверяем длинну строк
if (strlen($user_name)>255){$this->error = "User_name is more than 255 characters"; return FALSE;}
if (strlen($user_email)>255){$this->error = "Email is more than 255 characters"; return FALSE;}
if (strlen($password)>255){$this->error = "Password is more than 255 characters"; return FALSE;}

//Проверям правильность email
if (filter_var($user_email, FILTER_VALIDATE_EMAIL)=== false){$this->error = "Sender adress is invalid email adress"; return FALSE;}

//После проверки правильности если все прошло можно уже загрузить и либу для почты
$this->CI->load->library('simple_mail_lib');


$this->CI->session->Usermodel->insert_user_registration($user_name,$user_email,$password);

<<<<<<< HEAD

$this->CI->Usermodel->insert_user_registration($user_name,$user_email,$password);


//После проверки правильности если все прошло можно уже загрузить и либу для почты
$this->CI->load->library('simple_mail_lib');
=======
>>>>>>> a5172c80c2dd0121838abb64d8b5e5f6a5e5e785
//Определяем параметры для отправки письма
$from = $this->CI->simple_mail_lib->global_from;
$to = $user_email;



$subject = "Welcome to our site! It's a quite nice place to be";
$text = "Hello our new dear friend " . $user_name . " take a look and be like home!";

//Если вдруг не получилось отправить письмо.
//Как обрабатывать ошибку
if (!$this->CI->simple_mail_lib->send_mail($from,$to,$subject,$text))
{
  echo "Error while sendind mail: " . $this->CI->simple_mail_lib->error_send;
  die();
}
return true;


}






//Функция которая возвращает данные юзера
//А также обновляет последнюю активность пользователя в базе данных
private function _get_user_data_and_update_last_activity($input_id)
{
$user_data= $this->CI->Usermodel->find_user_exist_and_return_user_data_by_id($input_id);
//Проверяем не пустой ли массив с юзером вернули.
if ($user_data)
{
//Поскольку мы обновляем это число нам нужно его поменять и в массиве
$user_user_last_active_date = $this->CI->Usermodel->update_user_last_activity($input_id);
$user_data['user_last_active_date2'] = $user_user_last_active_date;
return $user_data;
}
else {
return false;
}
}












        public function get_int()
        {
          return SELF::$test_int;
        }

        public function get_int_with_increment()
        {
          return SELF::$test_int++;
        }










        public function get_user_data()
        {
             $this->CI->load->model('Usermodel');
         // $arr =  $this->CI->Usermodel->get_first_user();

           $bool =  $this->CI->Usermodel->get_first_user();

            $this->user_id   = intval($bool['id']);
            $this->user_name = $bool['user_name'];



            echo "<br>";
            echo "It's a lib";
            echo "<br>";
            echo $bool['user_name'];
			echo "<br>";
			echo $bool['id'];
			echo "<br>";
			echo $bool['user_email'];
			echo "<br>";


        }

        public function bar()
        {
          if ($this->iflogginned)
          {
		  	    echo("<br>");
          echo("bar");

		  } else{
		  	redirect('Access_denied', 'refresh');
        //die();
		  }


        }

}
?>
