<?php
class CapchamodelFirst extends CI_Model {


    public function validateCaptcha()
    {
//Берем данные
        $c1_user_sent=$this->input->post('reg_captcha1');
        $c1_correct_char  =$this->session->flashdata('reg_captcha1');


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

    }


    public function generateCaptcha()
    {

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





}
?>