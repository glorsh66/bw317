<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model3 extends CI_Controller {


	public function index()
	{
	$this->load->model('User_groups_model');	
	$this->load->library("simple_auth_lib");
	echo $this->simple_auth_lib->user_login("user11","test55");
	
	
	
	   
	}


}
?>