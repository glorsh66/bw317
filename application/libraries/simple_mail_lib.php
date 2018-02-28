<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class simple_mail_lib {
public $error_send = "";

//Глобальные переменные
public $global_from = "admin@bw317.ru";
public $global_name = "Glav_Admin";

//Тестовые переменные
public $ammount_of_processed_mails = 0;
public $time_elapsed =0;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
                $this->CI->load->model('Mailmodel');
        }

//Функция_отправить_письмо_через_базу
public function send_mail($from,$to,$subject,$text) : bool
{
//Проверям пустые ли строки
if (empty($from)){$this->error_send = "Sender adress is empty"; return FALSE;}
if (empty($to)){$this->error_send = "Reciver adress is empty"; return FALSE;}
if (empty($subject)){$this->error_send = "Subject of the mail is empty"; return FALSE;}
if (empty($text)){$this->error_send = "Text of the mail is empty"; return FALSE;}

//Проверяем длинну строк
if (strlen($from)>255){$this->error_send = "Sender adress is more than 255 characters"; return FALSE;}
if (strlen($to)>255){$this->error_send = "Reciver adress is more than 255 characters"; return FALSE;}
if (strlen($subject)>255){$this->error_send = "Subject is more than 255 characters"; return FALSE;}
if (strlen($text)>21000){$this->error_send = "Text is more than 21.000 characters"; return FALSE;}

//Проверям правильность email
if (filter_var($from, FILTER_VALIDATE_EMAIL)=== false){$this->error_send = "Sender adress is invalid email adress"; return FALSE;}
if (filter_var($to, FILTER_VALIDATE_EMAIL)=== false){$this->error_send = "Reciver adress is invalid email adress"; return FALSE;}

//Записываем в базу
//Если все норм то записываем в базу
$this->CI->Mailmodel->insert_mail($from,$to,$subject,$text);
return TRUE;
}



//Функция которая отправяет письма
//Берет из базы данных $ammount записей
//отправялет их через library CI - mail

//Данная функция будет скорее всего вызываться через консоль
//Что бы не увеличивать время отклика
public function actually_send_mail(int $ammount, float $max_time)
{
$start = microtime(true); //Замеряем время выполнения скрипта
$this->CI->load->library('email'); //Она нужна толкько в этой функции

$mails = $this->CI->Mailmodel->get_mails($ammount);
$sended_mails = 0;

foreach ($mails as $mail)
{
$this->CI->email->from($mail['mail_from'], $this->global_name);
$this->CI->email->to($mail['mail_to']);
$this->CI->email->subject($mail['mail_subject']);
$this->CI->email->message($mail['mail_text']);
$this->CI->email->send();
$this->CI->Mailmodel->update_has_been_sent($mail['id']);
$this->ammount_of_processed_mails++;
$sended_mails++;
//TODO:Сделать функцию для завершения скрипта если слишком долго отправляет письма (Время выполнения брать из аргумента)
}


$time_elapsed_secs = microtime(true) - $start;
$this->time_elapsed=$time_elapsed_secs;
return $sended_mails;

}

}
?>
