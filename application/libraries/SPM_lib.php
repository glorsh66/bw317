<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SPM_lib {

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


}
?>
