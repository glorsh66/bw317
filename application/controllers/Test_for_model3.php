<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model3 extends CI_Controller {


	public function index()
	{

	$this->load->library("simple_auth_lib");
	$this->load->model("PMmodel");
	$this->PMmodel->insert_message(2,1,'otvetka');
	$this->PMmodel->insert_message(3,1,'otvetka');
	$this->PMmodel->insert_message(4,1,'otvetka');
	$this->PMmodel->insert_message(5,1,'otvetka');
	$this->PMmodel->insert_message(6,1,'otvetka');
	$this->PMmodel->insert_message(7,1,'otvetka');

	   
	}


}
?>