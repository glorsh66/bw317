<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {


public function activate($code =null)
{
    $ret = $this->check_activation($code);
    if (!$ret['error']===FALSE)//Ошибка
    {
    //TODO: Переделать так, что бы использовать view а не echo (когда уже будет весь интерфейс построен).
    Echo '<b>Ошибка активации учетной записи пользователя</b><br>'.
        $ret['error_message'];
    }
    else //Успех
    {
    //TODO: Переделать так, что бы использовать view а не echo (когда уже будет весь интерфейс построен).
        Echo 'Ваша учетная запись была успешно активирована, вы теперь можете полностью свободно пользоваться возможностями сайта';
    }

}

public function another_code()
{

$this->benchmark->mark('start');
    if ($this->simple_auth_lib->check_if_user_is_loggined() === TRUE) //Пользователь залогинен
    {
        if ( ((int)$this->simple_auth_lib->user_data['isactivated'])===0 ) // Если пользователь уже не активирован
        {
        $id = (int)$this->simple_auth_lib->user_data['id'];
        $row = $this->Usermodel->get_activation_code_by_id($id); //Берем строку

            if (!empty($row) && is_array($row)) // Если есть такая строка в таблице активации.
            {
                //Сверяем даты:
        $datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $row["user_activation_code_change_time"] );
        $datetime_before = new DateTime();
        $datetime_before->modify('-30 minutes');

                if ($datetime_before>$datetime)//Можно отсылать код.
                {
                    $this->generate_new_code($id);
                    $this->benchmark->mark('stop');
                    //TODO: Переделать так, что бы использовать view а не echo (когда уже будет весь интерфейс построен).
                    echo 'Новый код отправлен на почту'.'<br>';
                    echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
                }
                else
                {
                    //TODO: Переделать так, что бы использовать view а не echo (когда уже будет весь интерфейс построен).
                    $this->benchmark->mark('stop');
                    echo 'Нельзя отправлять код повторно чаще чем каждые 30 минут'.'<br>';
                    echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
                }
            }
        }
    }
}




private function check_activation($code)
{
    $ret['error'] = FALSE;
    $ret['error_message'] = "";


    if (empty($code)) // Если пустая строка
    {
    $ret['error'] = TRUE;
    $ret['error_message'] = "Ошибка активации - Пустая строка активации. Проверьте что вы прошли по ссылки которая Вам пришла 
    на почту, или правильно в ручную ввели строку";
    return $ret;
    }

    $row = $this->Usermodel->get_activation_code_by_code($code); //Берем строку
    if (empty($row) || !is_array($row))
    {
    $ret['error'] = TRUE;
    $ret['error_message'] = "Ошибка активации - Выввели неправильную строку активации 
    Проверьте что вы прошли по ссылки которая Вам пришла 
    на почту, или правильно в ручную ввели строку";
    return $ret;
    }

    $id = (int)$row['user_that_will_be_activated_id']; //Айдишник пользователя

    //Проверяем дату получения кода активации (если уже прошло два дня)
    $datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $row["user_activation_code_change_time"] );
    $datetime_two_days_before = new DateTime();
    $datetime_two_days_before->modify('-2 day');

    if ($datetime_two_days_before>$datetime)
    {
    $ret['error'] = TRUE;
    $ret['error_message'] = "Ошибка активации - Ваш код активации устарел. На Вашу почту отправлен новый код активации";
    $this->generate_new_code($id);
    return  $ret; //Возвращаем в итоге ошибку
    }

    //Успешно активируем пользователя Если небыло никаких ошибок;
    $this->Usermodel->delete_activation_code($id);
    $this->Usermodel->activate_user($id);
    return $ret; // По умолчанию там FALSE в ERROR по этому если небыло ошибок то все норма будет
}

private function generate_new_code(int $id)
{
    $random_validator = bin2hex(random_bytes(100));
    $sha256_validator = hash('sha256', $random_validator);
    $sha256_validator = $sha256_validator. ((string)$id);
    $this->Usermodel->update_activation_code($id,$sha256_validator);

    $usr = $this->Usermodel->find_user_exist_and_return_user_data_by_id($id); //Берем данные пользователя, что бы получить почту

    $this->load->library('simple_mail_lib');
    $mail_subject = "Повторная отправка кода регистрации";
    $mail_text = "Повторно отправляем код активации учетной записи пользователя на сайте: " .  $sha256_validator;
    $this->simple_mail_lib->send_mail($usr['user_email'],$mail_subject,$mail_text);
}






public function __construct()
{
        parent::__construct();
        $this->load->model("PMmodel");
        $this->load->model('Usermodel');
        $this->load->library('simple_auth_lib');
        $this->load->helper('url');
        $this->config->set_item('language', 'russian');

}


    public function index()
{
    $this->auth();
}




//Функция для захода юзера
		public function auth()
	{

		
		$show_captcha = FALSE;
		$data['show_captcha'] = FALSE;
		$data['wrong_name_or_email'] = False;
		$data['wrong_password'] = False;
		
		
		
		
$this->form_validation->set_rules('auth_username', 'username or @e-mail', 'trim|required|min_length[1]|max_length[255]|xss_clean');
$this->form_validation->set_rules('auth_password', 'Password', 'trim|required|min_length[4]');


if($show_captcha)
{
	        if(!$this->session->user_auth_capcha_text){
            $this->session->user_auth_capcha_text = strval(rand(10000,99999));
}

$this->form_validation->set_rules('captcha_tf', 'Captcha', 'trim|required|callback__check_captcha_user_auth');
$data['show_captcha'] = TRUE;
}


if($this->form_validation->run())
{ //Если проверка на  шаблоны прошла успешно то
		  //Берем данные из формы и вставляем в переменные
		  
$user_name_or_email = $this->input->post('auth_username');
$password = $this->input->post('auth_password');
 
 if($show_captcha)
 {
 $this->session->unset_userdata('user_auth_capcha_text'); 	
 }
  
if($this->Usermodel->find_user_exist($user_name_or_email)) 
{
$data['user_exist'] = "Да существует";		
}else
{
$data['user_exist'] = "Не существует";		
}


if($this->Usermodel->find_user_whith_pass_exist_true_or_false($user_name_or_email,$password)) 
{
$data['pawrod_is_not_correct'] = "Пароль правильный";		
}else
{
$data['pawrod_is_not_correct'] = "Пароль, не правильный";		
}
  

}	
		
$this->load->view('User_auth_view',$data);	
}

//Функция для захода юзера end
	























public function update_e()
	{
		$this->load->model('Usermodel');
		$this->Usermodel->update_entry();
	}
	
	
	public function find_u()
	{
		$this->load->model('Usermodel');
		
		$output= $this->Usermodel->find_user_whith_open_pass('clopq','clopq@yadnex.ru','clop10');
		
		if ($output == TRUE)
		{
			echo 'TRUE';
		} else 
		{
		echo 'False';
		
		}
	}
		
		
	public function registration()
	{


		//Грузим Нужные библиотеки
	    $this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('form');	
	//	$this->load->helper('captcha');	
		$this->load->library('form_validation');	
		$this->load->helper('security');	
		$this->load->database();
		$this->load->model('Usermodel');
		

		//Проверка на существовании сессии с текстом капчи
		//Если нет такой то создаем новую сессиию
        if(!$this->session->registration_capcha_text){
            $this->session->registration_capcha_text = strval(rand(10000,99999));
        }


		//Устанавливаем правила проверки форм
        $this->form_validation->set_rules('reg_username', 'Username', 'trim|required|min_length[3]|max_length[50]|xss_clean|is_unique[site_users.user_name]|regex_match[/^([a-zA-Z0-9]|\s)+$/]|callback__check_anonymous');
		//Поле проверяется, что бы не было заняты имена такие как anonymous1 - anonymous10
		$this->form_validation->set_rules('reg_email', 'Email', 'trim|required|valid_email|xss_clean|is_unique[site_users.user_email]');
        $this->form_validation->set_rules('reg_password', 'Password', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('reg_cpassword', 'Password Confirmation', 'trim|required|matches[reg_password]');
        $this->form_validation->set_rules('captcha_tf', 'Captcha', 'trim|required|callback__check_captcha');
        
		
		//Проводим проверку полей 		
		if($this->form_validation->run())
		{ //Если проверка на  шаблоны прошла успешно то
		  //Берем данные из формы и вставляем в переменные
		  $user_name = $this->input->post('reg_username'); //
    	  $user_email =$this->input->post('reg_email');
    	  $password = $this->input->post('reg_password');
				
		  //Вызываем модель Usermodel, которая вставляем данные для регистрации пользователя 		
		$this->Usermodel->insert_user_registration_form($user_name,$user_email,$password);
		$this->session->unset_userdata('registration_capcha_text');
		}
		$this->load->view('User_registration_view');


		
	}


	//Каллбак функции
	//Калл-бак функция - Поле проверяется, что бы не было заняты имена такие как anonymous1 - anonymous10
	public function _check_anonymous($str){
		   $str_lc =strtolower($str);		   
		   if(preg_match('/^anonymous[0-9]/',$str_lc)){
           $this->form_validation->set_message('_check_anonymous','You can\'t use names like: anonymous1 or anonymous2 or something similar');	
		   	return FALSE;
		   }else{
		   	return TRUE;
		   }
		}
  //каллбак функции


	//Каллбак функции
	//Каллбак функция проверки капчи
    public function _check_captcha($str){
        if($this->session->registration_capcha_text && $this->session->registration_capcha_text == $str ){
           return true;
        } else
        {
            $this->form_validation->set_message('_check_captcha','Wrong captcha provided');
            return FALSE;
        }
    }
    
        
        public function _check_captcha_user_auth($str){
        if($this->session->user_auth_capcha_text  == $str ){
           return true;
        } else
        {
            $this->form_validation->set_message('_check_captcha_user_auth','Wrong captcha provided');
            return FALSE;
        }
    }
	//каллбак функции

	//каллбак функции



public function t(){
		   $this->load->database();		   
		   $this->db->where('id',111);
		   $query = $this->db->get('site_users');
		   echo $this->db->last_query();
		   echo '<br>';
		   echo 'номер результатов '. $query->num_rows();
		   echo '<br>';
		   foreach ($query->result() as $row)
{
         
         echo $row->id;
         echo '<br>';
         echo $row->user_name;
         echo '<hr>';


}

if(isset($row->user_name)){
	

$danger =  $row->user_name;


		   $this->db->where('user_name',$danger);
		   $query = $this->db->get('site_users');
		   echo $this->db->last_query();
		   echo '<br>';
		   echo 'номер результатов '. $query->num_rows();
		   echo '<br>';
		   
		   
		   foreach ($query->result() as $row)
{
         
         echo $row->id;
         echo '<br>';
         echo $row->user_name;
         echo '<hr>';


}

		}	
			
		}
	
	
	
}

	


?>