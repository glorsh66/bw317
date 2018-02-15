<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model4 extends CI_Controller {


	public function index()
	{
	$this->load->model('User_groups_model');	
	$this->load->library("simple_auth_lib");
	$this->load->model("Login_attempts_model");
	
	
	$this->Login_attempts_model->insert_new_entry("test_guy");
	$this->Login_attempts_model->insert_new_entry("test_guy");
	$this->Login_attempts_model->insert_new_entry("test_guy");
	$this->Login_attempts_model->insert_new_entry("test_guy");
	$this->Login_attempts_model->insert_new_entry("test_guy");
	$this->Login_attempts_model->insert_new_entry("test_guy");
	
	

	
	   
	}


}
?>