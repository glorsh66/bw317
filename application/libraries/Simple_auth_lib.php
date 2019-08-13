<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_auth_lib {
  public $user_cause_of_error = "";
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


    /**
     * Функция которая работает когда пользователй ввел даные с формы
     * возвращает boolean в зависимости от того успешно ли прошла авторизация или нет
     * Если ошибка всетаки встречена то заполняет свойства объекта которые равны этому значению
     *
     * Если пользователь авторизируется - добавляет кукисы и сессию.
     * @param string $user_name_or_email_form
     * @param string $password_from_form
     * @param bool $remeber_me
     * @return bool
     * @throws Exception
     */
    public function user_login_from_form(string $user_name_or_email_form, string $password_from_form , bool $remeber_me=true) : bool
{
  //Проверям данные которые пришли с формы
  //(по хорошему нужно проверять еще на длинну)
  if (empty($user_name_or_email_form))
  {
    $this->error_user_name_or_email_form_is_empty = "Username or email sting from form is empty";
    return FALSE;
  }

  if (empty($password_from_form))
  {
    $this->error_password_from_form = "Password is empty";
    return FALSE;
  }

  //Ищем пользователя по Login or Email
  $this->user_data = $this->CI->Usermodel->find_user_exist_and_return_user_data($user_name_or_email_form);

  //Проверям не вернулся ли фалс
  if ($this->user_data) {

  //Проверям сходится ли пароль
  //Смотрим сколько было попыток захода

  //Первым делом, смотрим не заблокирован ли пользователь из-за большого колличества попыток зайти
  //Если пользователь заблокирован то сразу возвращаем FALSE что бы не тратить ресурсы на вытаскивание из базы и подсчет
  //Попыток зайти в данного пользователя


  //Старая имплементация с использованием объекта DateTime::
  //$datetime_blocked_by_date = DateTime::createFromFormat ( "Y-m-d H:i:s", $this->user_data['blocked_by_date'] );
  //$date_now = new DateTime("now");

//Если заблокирован на
if ( ((int)$this->user_data['blocked_up_to_date']) >= time())
  {
      $this->error_limit_for_login_attemts_with_wrong_password_is_exceeded = 'Ваш пользователь заблокирован на некоторое
       время. Из-за большого количества попыток неправильно ввести пароль. Это продлится около 10 минут. Сходите выпейте
        кофе или лучше зеленого чаю, съешьте мягких французских булочек и вспомните пароль ';
      return FALSE;
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
  $this->is_user_loggined = TRUE; //Пользователь успешно зашел значит данные точно есть в переменной

  //Выставляем число попыток захода с неправильным паролем в 0
  $this->CI->Usermodel->set_zero_login_attempts_with_wrong_password((int)$this->user_data["id"]);

  return TRUE; // Возвразаем true пользователь зашел.

  }// конец иф, если не верный пароль
  else
  {
  //Добавляем инфу о попытке захода пользователя


//Посчет попыток с неправильно введеным паролем
$ten_minutes = time()+600; // +10 минут от текущего момента
$less_ten_minutes = time()-600; // - 10 минут от текщего времени

//Колличество попыток войти с неправильным паролем на текущий момент
$logging_attempts_with_wrong_password = (int)$this->user_data['tries_with_wrong_password'];

if ( ((int)$this->user_data['last_time_wrong_pass_unix_tsmp']) >= $less_ten_minutes)
{
    //Сюда заходим только если колличество попыток уже превышает 10. (максимальное значение)
    if ($logging_attempts_with_wrong_password >= SELF::max_tries_with_incorrect_password) {
        $this->error_limit_for_login_attemts_with_wrong_password_is_exceeded = "Вы пытались ввести неправильный пароль 
слишком много раз. Попытайтесь повторить через некоторое время, минут через 10.";
        $this->CI->Usermodel->block_user_on_x_minutes($this->user_data['id'], $ten_minutes);
        return FALSE;
    }

//Если в течении 10 минут уже вводил неправильно пароль, то значение попыток инкриментиится
$this->CI->Usermodel->insert_login_attempts($this->user_data["id"],TRUE);
}
else{//Если уже 10 минут прошли с последних попыток ввести неправильно пароль, то выставляется 1 в коллисетсо попыток
    $this->CI->Usermodel->insert_login_attempts($this->user_data["id"],FALSE);
    $logging_attempts_with_wrong_password=0;}


  if ($logging_attempts_with_wrong_password > 0 &&
      $logging_attempts_with_wrong_password <=SELF::max_tries_with_incorrect_password)
  {
    $this->error_password_is_wrong = "Вы ввели неправильный пароль. Будь внимателен! У тебя осталось только "
    . (SELF::max_tries_with_incorrect_password-($logging_attempts_with_wrong_password)) . " "
    ." из максимального числа попыток ". SELF::max_tries_with_incorrect_password  . " прежде чем твой аккаунт будет заблокирован 
    минуток так на 10. Так, что вводи пароль внимательно.";
    return FALSE;
  }
  else
  {
    $this->error_password_is_wrong = "Неправильный пароль.";
    return FALSE;
  }
  }
  } //конец иф, если не найден пользователь
  else
  {
    $this->error_user_is_not_found = "Пользователь не найден";
    return FALSE;
  }
  return FALSE;
}//end of the function



//Функция которая проверят залогинен ли пользователь
//Вначале проверям переменную (данные пользователя), что бы не вызывать функцию по 10 раз
//Если функция пустая, то проверям вначале сессиию
//А потом уже проверям куки. (И если кукисы сработали, то мы пересоздаем сессиию, что бы с неё брать инфу)

//Данные о пользовтеле притаскивают из базы данных каждый раз (так как он может быть забанен)
public function check_if_user_is_loggined(): bool
{
//Если пользовтель уже залогинен
if ((!is_null($this->user_data) && ($this->is_user_loggined===TRUE)))
{
return TRUE;
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
    return FALSE;
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
  //TODO: Нужно ли тут обновлять сессиию? Вот в чем попрос.
  $this->CI->session->set_userdata('user_logged_id', $this->user_data["id"]);
  $this->CI->session->set_userdata('user_logged_name', $this->user_data["user_name"]);
  $this->CI->session->set_userdata('user_logged_email', $this->user_data["user_email"]);
  $this->CI->session->set_userdata('user_logged_group_id', $this->user_data["group_id"]);
  $this->is_user_loggined=TRUE; //Прописываем
  return TRUE;
} else { //Если не вернул данных для польхователей из базы
  return FALSE;}
} else {return false;} //Не в сессии не в куках не нашлось USER_ID

return FALSE; // Если по какой то причине дошли до этой строки
}


public function register($user_name,$user_email,$password)
{
//Грубая проверка данных что бы не допустить значений которые база не может принять
//Проверям пустые ли строки
if (empty($user_name)){$this->error = "User_name is empty"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}
if (empty($user_email)){$this->error = "Email is empty"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}
if (empty($password)){$this->error = "Password is empty"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}

//Проверяем длинну строк
if (strlen($user_name)>255){$this->error = "User_name is more than 255 characters"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}
if (strlen($user_email)>255){$this->error = "Email is more than 255 characters"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}
if (strlen($password)>255){$this->error = "Password is more than 255 characters"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}
//Проверям правильность email
if (filter_var($user_email, FILTER_VALIDATE_EMAIL)=== false){$this->error = "Sender adress is invalid email adress"; die('Критическая ошибка при сохранении пользователя: ' . $this->error);}


$this->CI->load->library('simple_mail_lib');
//Определяем если нужно активировать пользователя по почте
if ($this->CI->config->item('my_activate_user_by_mail')===TRUE)//активировать пользовалетя по почте
{
$ret_usr_id = $this->CI->Usermodel->insert_user_registration($user_name,$user_email,$password,TRUE);

$random_validator = bin2hex(random_bytes(100));
$sha256_validator = hash('sha256', $random_validator);
$sha256_validator = $sha256_validator. ((string)$ret_usr_id);

$this->CI->Usermodel->insert_user_activation_code($ret_usr_id,$sha256_validator);

$mail_subject = "Добро пожаловать на наш сайт! Пожалуйста потвердите Вашу регистрацию";
$mail_text = "Добро пожаловать на наш сайт! Остался всего один шаг, и вы сможете абсолютно бесплатно пользоваться нашим сайтом
Пожалуйста пройдите введите следующий код потверждения Вашей регистрации: " .  $sha256_validator;
$this->CI->simple_mail_lib->send_mail($user_email,$mail_subject,$mail_text);
}

else //Если не нужно никакой почты и пользователь сразу активирован, как только зарегился
{
    $ret_usr_id = $this->CI->Usermodel->insert_user_registration($user_name,$user_email,$password,FALSE);
    $mail_subject = "Добро пожаловать на наш сайт!";
    $mail_text = "Добро пожаловать на наш сайт! Вы можете начинать пользоваться сайтом без каких либо дальнейших действий." ;
}

return $ret_usr_id;
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


    /**
     * Функция которая возвращает все обобщенные ошибки одной строкой.     *
     * @return string
     */
public function get_errors():string
{
    $retstr = "";
    $retstr= $retstr. ' ' . $this->error_user_name_or_email_form_is_empty;
    $retstr= $retstr. ' ' .$this->error_password_from_form;
    $retstr= $retstr. ' ' .$this->error_limit_for_login_attemts_with_wrong_password_is_exceeded;
    $retstr= $retstr. ' ' .$this->error_password_is_wrong;
    $retstr= $retstr. ' ' .$this->error_user_is_not_found;
    return $retstr;
}


    /**
     *функция удаляет куки и сесси пользователя
     */
    public function log_out()
{
    delete_cookie('user_selector');
    delete_cookie('user_validator');
    $this->CI->session->unset_userdata('user_logged_id');
    $this->CI->session->unset_userdata('user_logged_name');
    $this->CI->session->unset_userdata('user_logged_email');
    $this->CI->session->unset_userdata('user_logged_group_id');
    $this->CI->session->sess_destroy();
}






}
?>
