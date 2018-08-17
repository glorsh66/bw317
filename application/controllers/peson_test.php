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

    $fp = new form_params(form_params::select,'Ничего не выбрано',array('required'),'Ошибочка','pref_',TRUE,"Ошибочка вышла, не тот класс ты выбрал");
    $fp2 = new form_params(form_params::select,'Ничего не выбрано',[],'Ошибочка','pref_',TRUE,"Ошибочка вышла, не тот класс ты выбрал");
    $fp3 = new form_params(form_params::radio,'Ничего не выбрано',['required'],'Ошибочка','pref_',TRUE,"Ошибочка вышла, не тот класс ты выбрал");
    $f1 = new form_field_new_person('sex',[1=>'мужчина',2=>'женщина'],$fp,'Ваш пол:');
    $f2 = new form_field_new_person('age',[1=>'18-25',2=>'26-35',3=>'36-42'],$fp,'Ваш возраст:');
    $f3 = new form_field_new_person('req1',[1=>'yes',2=>'no',3=>'I don\' know'],$fp2,'Необходимо заполнить');
    $f4 = new form_field_new_person('req2',[1=>'yes',2=>'no',3=>'I don\' know'],$fp3,'Необходимо заполнить');

    $f1->get_val_rules();
    $f2->get_val_rules();
    $f3->get_val_rules();
    $f4->get_val_rules();



    $data['f1']=$f1;
    $data['f2']=$f2;
    $data['f3']=$f3;
    $data['f4']=$f4;



    if ($this->form_validation->run() == FALSE)
    {

        $this->load->view('person_test_view',$data);
       echo 'Форма1: '. $this->input->post($f1->get_name());
       echo 'Форма2: '. $this->input->post($f2->get_name());
       echo 'Форма3: '. $this->input->post($f3->get_name());
       echo 'Форма3: '. $this->input->post($f4->get_name());
       $this->benchmark->mark('stop');
       echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
    }
    else
    {
        foreach ($data as $d)
        {
            echo $d->name . ':  '  .$d->post_value();;
            echo '<br>';
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