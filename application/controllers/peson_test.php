<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class peson_test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Usermodel');
        $this->load->model('Personmodel');
        $this->load->library('simple_auth_lib');
    }




public function index()
{
    $this->load->library('form_validation');
    $this->load->helper('form');






    $this->benchmark->mark('start');

    //Определяем наборы параметров для форм
    $fp_sel_req = new form_params(form_params::select,'Не указано',array('required'),'select_required','person_',TRUE,"Ошибочка 1");
    $fp_radio_req = new form_params(form_params::radio,'Не указано',array('required'),'radio_required','person_',TRUE,"Ошибочка 2");
    $fp_radio_req2 = new form_params(form_params::radio,'Не указано',array('required'),'radio_required','person_',TRUE,"Ошибочка вышла, не тот класс ты выбрал");
    $fp_sel_notreq = new form_params(form_params::select,'Не указано',array('numeric'),'select_not_required','person_',TRUE,"Ошибочка вышла, не тот класс ты выбрал");

    //Делаем массив форм, для того что бы потом их можно было удобно выбирать во вьюхе в зависимости от нужного дизайна
    //Т.е. например мы можем выбрать с 0 по 8 для одного блока форм и дальше

    //TODO: проверить орфографию
    $ar[0] = new form_field_new_person('sex',[1=>'мужчина',2=>'женщина'],$fp_radio_req,'Ваш пол');
    $ar[1] = new form_field_new_person('sexual_orientation', array(1 => 'гетеро', 2 => 'лесби', 3=> 'бисексуал', 4=>'пансексуал'),
        $fp_radio_req2,'Сексуальная ориентация');
    $ar[2] = new form_field_new_person('relationship',array(1 => 'свободен', 2 => 'в отношениях', 3=> 'в гражданском браке', 4=>'в браке', 5=>'разведен')
        ,$fp_sel_req,'Текущие отношения');
    $ar[3] = new form_field_new_person('education',array(1 => 'среднее',2=>'срднее-специальное', 3 => 'высшее', 4=> 'нескольких высших',
        5=>'ученая степень', 6=>'иностранное образование'),$fp_sel_req,'Ваш пол');
    $ar[4] = new form_field_new_person('income',array(1 => 'нет дохода',2=>'небольшой доход', 3 => 'средний доход', 4=> 'высокий доход',
        5=>'непостоянные заработки'),$fp_sel_notreq,'Доход');
    $ar[5] = new form_field_new_person('employment',[1 => 'безработный',2=>'непостоянные заработки', 3 => 'фрилансер', 4=> 'предприниматель',
        6=>'живу на доход от чего то', 7=>'непостоянные заработки', 8=>'стабильная работа', 9=>'руководитель'],$fp_sel_req,'Ваша деятельность');
    $ar[6] = new form_field_new_person('smoke',[1 => 'заядлый курильщик', 2 => 'курю иногда', 3 => 'курю когда выпью',
        4 => 'изредка балуюсь', 5 => 'не курю', 6 => 'Никогда не пробовал курить'],$fp_sel_req,'Ваш пол:');
    $ar[7] = new form_field_new_person('alcohol',[1 => 'пью регулярно', 2 => 'пью за компанию (социально)',3 => 'пью изредко',
        4 => 'почти не пью',5 => 'противник алкоголя',5 => 'никогда не пробовал алкоголь', 6 =>'не пью'],$fp_sel_req,'Ваш пол:');
    $ar[8] = new form_field_new_person('sport',[1 => 'Профессиональный спортсмен',  2 => 'Мастер спорта', 3 =>'Занимаю регулярно',
        4 => 'Иногда знимаюсь', 5 => 'Изредка занимаюсь', 6 => 'Не занимаюсь'],$fp_sel_req,'Ваш пол:');
    $ar[9] = new form_field_new_person('health',[1 => 'есть проблемы со здоровьем', 2 => 'небольшие проблемы со здоровьем', 3 => 'в целом все нормально',
        4 => 'здоров', 5 => 'отличное здоровье'],$fp_sel_req,'Ваш пол:');
    $ar[10] = new form_field_new_person('virus_hiv',[1 => 'Носитель', 2 => 'Нет'],$fp_sel_req,'Ваш пол:');
    $ar[11] = new form_field_new_person('virus_hepatitis_c',[1 => 'Носитель', 2 => 'Нет'],$fp_sel_notreq,'Ваш пол:');




    //TODO урать
    $eee = new form_field_new_person('virus_hepatitis_c',[1 => 'Носитель', 2 => 'Нет'],$fp_sel_req,'Ваш пол:');




    //Устанавливаем правила FORM VALIDATION
    foreach ($ar as $el)
    {
        $el->get_val_rules();
    }

    //Массив данных для передачи в VIEW
    $data['person_forms'] = $ar;


    if ($this->form_validation->run() == FALSE) //Если не прошли форма валидацию
    {

       $this->load->view('person_test_view',$data);
    }
    else // Успешно проши валидацию
    {
        //Выводим все значения. из массива
        foreach ($ar as $el)
        {
         $name = $el->get_name();
         $val = $this->input->post($name);
         if (is_numeric($val)) $val=(int) $val;
         else $val = 0;
         $st = "Данные для поля: " . $el->get_name() . 'значение: ' . 'Цифрой ' . $val = $this->input->post($name).
                 ' Буквой: ' . $el->get_text_representation($val).'<br>';
         echo $st;
        }
    }



}






public function q()
{

for ($i = 21; $i <=300000 ; $i++)
{

//Вначале вставляем данные юзера
$user_name= 'test_user_'.$i;
$user_email= 'test_user_'.$i.'@mail.ru';
$user_password = 'pass';
$user_id = $this->simple_auth_lib->register($user_name,$user_email,$user_password);

//Потом вставляем персону, используя полученный id_шник
$id= $user_id;
$height= rand(150,230);
$weight=  rand(45,200);
$children=  rand(0,9);
$sex=  rand(1,2);
$sexual_orientation= rand(1,5);
$relationship=  rand(1,5);
$education=  rand(1,5);
$employment=  rand(1,5);
$smoke= rand(1,5);
$alcohol=  rand(1,5);
$sport= rand(1,6);
$health=  rand(1,5);
$virus_hiv= rand(1,2);
$virus_hepatitis_c= rand(1,2);
$this->Personmodel->insert_person($id,$height,$weight,$children,$sex,$sexual_orientation,$relationship,$education,$employment,$smoke,
$alcohol,$sport,$health,$virus_hiv,$virus_hepatitis_c);

}



}


}
?>