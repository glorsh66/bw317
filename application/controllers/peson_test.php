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
$smoke= rand(1,5);пше
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