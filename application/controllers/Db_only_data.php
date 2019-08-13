<?php
class Db_only_data extends CI_Controller {

        public function delete()
        {
                $this->load->dbforge();
                $this->dbforge->drop_table('site_users');
        }

	  public function index()
	  {
	  	$this->createdb();
	  }


	        public function createdb()
        {
            $this->load->model('Regionmodel');
            $resp = $this->Regionmodel->getAllThree(1,150);
            var_dump($resp);









//          //грузим либы
//          $this->load->helper('url');
//
//        	//Создаем таблицу пользователей
//          $this->load->dbforge();
//
//
//		//Добавляем Users groups
//$this->load->model('Usermodel');
//$this->Usermodel->insert_user_group("admin","The most important guy here period.");
//$this->Usermodel->insert_user_group("moderators","Can delete or edit posts");
//$this->Usermodel->insert_user_group("super_moderators","Can delete or edit posts");
//$this->Usermodel->insert_user_group("users","Just a regual Joe. Nothing special");
//$this->Usermodel->insert_user_group("shop-keepers","Guys who use site quite intensively.");
//
//		//Добавляем Users
//$this->Usermodel->insert_user_registration_with_any_user_group("admin","admin@admin.com","test55",1);
//$this->Usermodel->insert_user_registration("user1","user1@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user2","user2@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user3","user3@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user4","user4@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user5","user5@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user6","user6@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user7","user7@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user8","user8@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user9","user9@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user10","user10@mail.ru","test55");
//$this->Usermodel->insert_user_registration("user11","user11@mail.ru","test55");



		    }


}
?>
