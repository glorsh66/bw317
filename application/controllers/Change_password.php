<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class change_password extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usermodel');
        $this->load->library('simple_auth_lib');
        $this->load->helper('url');
        $this->config->set_item('language', 'russian');
        $this->load->library('form_validation');
        $this->load->helper('form');

    }

    public function index()
    {
        //Если пользователь залогинен, то ему нужно залогиниться.
        if ($this->simple_auth_lib->check_if_user_is_loggined() !== TRUE) {
            redirect('/login/', 'refresh');
        }


        //Устанавливаем правила проверки форм

        $this->form_validation->set_rules('reg_password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('reg_cpassword', 'Password Confirmation', 'required|matches[reg_password]');

        //Проверка текстовой капчи МАРК-1
        $c1_user_sent= $this->input->post('reg_captcha1');
        $c1_correct_char= $this->session->flashdata('reg_captcha1');

        $c2_user_sent= $this->input->post('reg_captcha2');
        $c2_correct_answer = $this->session->flashdata('reg_captcha2');


//Валидация для первой формы
        $this->form_validation->set_rules( 'reg_captcha1', 'Капча1',
            [ 'required',  [ 'capch_check', function ( $str ) use ($c1_user_sent,$c1_correct_char)
            {
                if ($c1_user_sent == TRUE && $c1_correct_char == TRUE)//Если что то есть внутри двух переменных
                {
                    $c1_user_sent = mb_strtolower($c1_user_sent);
                    $c1_correct_char =mb_strtolower($c1_correct_char);
                    return $c1_user_sent === $c1_correct_char;
                } else
                    return FALSE;
            } ] ],
            array('capch_check' => 'Вы неправильно ввели ответ на вопрос. Попробуйте еще раз подобрать букву. Докажите что вы не робот.'));


//Валидация для второй формы
                $this->form_validation->set_rules( 'reg_captcha2', 'Капча2',
            [ 'required',  [ 'capch_check', function ( $str ) use ($c2_user_sent,$c2_correct_answer)
            {
                if ($c2_user_sent == TRUE && $c2_correct_answer == TRUE)//Если что то есть внутри двух переменных
                {
                    $c1_user_sent = mb_strtolower($c2_user_sent);
                    $c2_correct_char =mb_strtolower($c2_correct_answer);
                    return $c1_user_sent === $c2_correct_char;
                } else
                    return FALSE;
            } ] ],
            array('capch_check' => 'Вы неправильно ввели ответ на вопрос. Попробуйте еще раз подумать и ответить на вопрос. Докажите что вы не робот.'));


        //Заносим проверку скрытых полей
        $hidden_fields_check = TRUE;
        $hidden_fields_check1 = 'yes'===$this->input->post('reg_chbx_yes');
        $hidden_fields_check2 =  '' === $this->input->post('reg_name');
        $hidden_fields_check3 =  !isset($_POST['reg_chbx_no']);
        if ($hidden_fields_check1===FALSE || $hidden_fields_check2===FALSE || $hidden_fields_check3===FALSE)
            $hidden_fields_check=FALSE;




        if ($this->form_validation->run() && $hidden_fields_check)
        { //Если проверка на  шаблоны прошла успешно то
            //Берем данные из формы и вставляем в переменные
            $password = $this->input->post('reg_password');

            //Меняем пароль
            $id=(int)$this->simple_auth_lib->user_data['id'];
            $this->Usermodel->changePassword($id,$password);

            //TODO: Тут еще можно реализовать удаление все остальных сессий. Выход из текущекого пользователя.
            $this->Usermodel->deleteAllSessionsForUser($id);
            $this->simple_auth_lib->log_out();

            $name = $this->simple_auth_lib->user_data['user_name'];
            $this->simple_auth_lib->user_login_from_form($name,$password,true);

            //Если вставка регистрационных данныъ прошла успешно
            $data['success_message'] = 'Сменили свой старый пароль на новый';
            $this->load->view('success_view',$data);



        }
        else //Сюда заходим только если форма не отправлена, либо не прошла валидацию.
        {
            //Тут мы должны формировать капчу.
            $captcha1 = $this->textcaptcha_mark_one();
            $captcha2 = $this->textcaptcha_mark_two();

            $this->session->set_flashdata('reg_captcha1',$captcha1['correct_char']);
            $this->session->set_flashdata('reg_captcha2',$captcha2[1]);

            $data['captcha1'] = $captcha1;
            $data['captcha2'] = $captcha2[0];

            $this->load->view('change_password_view',$data);
        }
        //Проводим проверку полей




    }


private function textcaptcha_mark_one()
{
    //TODO: Вынести в отдельную либу
    $arr[] = 'Земля';
    $arr[] = 'Луна';
    $arr[] = 'Громада';
    $arr[] = 'Солнце';
    $arr[] = 'Звезда';

    $nums_names[] = 'первую';
    $nums_names[] = 'вторую';
    $nums_names[] = 'третью';
    $nums_names[] = 'четвертую';
    $nums_names[] = 'пятую';
    $nums_names[] = 'шестую';
    $nums_names[] = 'седьмую';
    $nums_names[] = 'восьмую';
    $nums_names[] = 'девятую';
    $nums_names[] = 'десятую';


    //Определяем длинну массива в котором перечисленны проверяемые слова
    $arr_len = count($arr);

    //Определяем случайное слолово из массива
    $rand_element_of_array = rand(0, $arr_len - 1);
    $cur_str = $arr[$rand_element_of_array];

    //Определеям длинну выбранного случайного слова
    mb_internal_encoding("UTF-8");
    $cur_str_len = mb_strlen($cur_str);

    //Выбираем позицию случайного
    $rand_chart_at = rand(0, $cur_str_len - 1);
    $char = mb_substr($cur_str, $rand_chart_at, 1);

    //Возвращаем массив
    // 1) - Выбранное слово
    // 2) - Позицию нужной буквы интенгером
    // 3) - Позицию нужной буквы словом
    // 4) - Верную букву
    $ret['word'] = $cur_str;
    $ret['pos_char_int'] = $rand_chart_at;
    $ret['pos_char_word'] = $nums_names[$rand_chart_at];
    $ret['correct_char'] = $char;
    return $ret;
}

private function textcaptcha_mark_two()
{
$ar[] = ['Первая планета от солнца', 'меркурий'];
$ar[] = ['Вторая планета от солнца', 'венера'];
$ar[] = ['Первая планета от солнца', 'земля'];
$ar[] = ['Четвертая планета от солнца', 'марс'];
$ar[] = ['Пятая планета от солнца', 'юпитер'];

$arr_len = count($ar);
$rand_element_of_array = rand(0, $arr_len - 1);

return $ar[$rand_element_of_array];
}



}
?>
