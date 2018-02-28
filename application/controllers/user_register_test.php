<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_register_test extends CI_Controller {


	public function index()
	{

		//Загружаем либы
		$this->load->library('simple_auth_lib');
		$this->load->model("Usermodel");

  if ($this->simple_auth_lib->register("1","ee@rs.rr","dd")) {
  	echo "User add";
  }
	else {
		echo "Error occured: " . $this->simple_auth_lib->error;
	}
		}

}
?>
