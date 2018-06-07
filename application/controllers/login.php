<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {

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
        $this->benchmark->mark('start');
        //Проверяем если пользователь уже залогинен
        //Т.е. есть сессиия или уже есть кука.
        if ($this->simple_auth_lib->check_if_user_is_loggined() === TRUE)
        {
         $user_data = $this->simple_auth_lib->user_data;
         $this->load->view('show_user_view', $user_data);
         //Показываем ему окошко под кем он залогинен
         //И кнопку для выхода
        }

        //Пользователь не залогинен
        else // Пользователь не залогигнен. Показываем ему форму для ввода логин/пароля
        {
            $this->load->helper('form');
            $this->load->library('form_validation');

            //Временный переменые
//            $show_captcha = FALSE;
//            $data['show_captcha'] = FALSE;
//            $data['wrong_name_or_email'] = False;
//            $data['wrong_password'] = False;
            $this->form_validation->set_rules('auth_username_or_email', 'username or @e-mail', 'trim|required|min_length[4]|max_length[255]');
            $this->form_validation->set_rules('auth_password', 'Password', 'required|min_length[4]');




//            //Если нужно показывать капчу
//            if($show_captcha)
//            {
//                if(!$this->session->user_auth_capcha_text){
//                $this->session->user_auth_capcha_text = strval(rand(10000,99999));
//                $this->form_validation->set_rules('captcha_tf', 'Captcha', 'trim|required|callback__check_captcha_user_auth');
//                $data['show_captcha'] = TRUE;
//             }
//

            //Если проверка на  шаблоны прошла успешно то
            //Берем данные из формы и вставляем в переменные
            $data['auth_errors']=''; //Пустая строка для вывода строки с ошибками

            $val_run = $this->form_validation->run();// Первичная проверка.
            if($val_run===TRUE)//Проверям, что данные вообще соответсвуют общим требованиям
            {
            $user_name_or_email = $this->input->post('auth_username_or_email');
            $password = $this->input->post('auth_password');

            $remember_me = (bool) $this->input->post('auth_remember_me');

            //Проверяем логин и пароль пользователя
            //Если логинимся - то сразу записываем это дело в сессию/куки
            //И редиректим. Редиректим только если пользователь залогинелся. (если ошибки то выполняется слеуюущий блок)
            $asnwer = $this->simple_auth_lib->user_login_from_form($user_name_or_email,$password,$remember_me);
            if ($asnwer===TRUE)
                {
                    redirect('/'); //Редирект делается только если абсолютно все успешно.
                    return;
                }
                else
                {
                    $data['auth_errors'] = $this->simple_auth_lib->get_errors();
                }
            }
            //Этот участок кода выполняется только если пользователь не залогинелся. Иначе был бы редирект

            //Грузим и вьюшку
            $this->benchmark->mark('stop');
            $data['elapsed_time']= $this->benchmark->elapsed_time('start','stop');

            $this->load->helper('form');
            $this->load->view('login_form_view',$data);
            }



    }



    public function login()
    {

    }
    public function logout()
    {
        $this->simple_auth_lib->log_out();
        redirect('/login/');

    }


}
?>
