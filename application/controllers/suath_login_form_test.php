<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class suath_login_form_test extends CI_Controller {


	public function index()
	{

		//Загружаем либы
		$this->load->library('simple_auth_lib');

		$user_name_or_email_form = "user2";
		$password_from_form = "test55";

		echo  $this->simple_auth_lib->user_login_from_form($user_name_or_email_form
		,$password_from_form ,1);

		echo $this->simple_auth_lib->error_user_name_or_email_form_is_empty;
		echo $this->simple_auth_lib->error_password_from_form;
		echo $this->simple_auth_lib->error_limit_for_login_attemts_with_wrong_password_is_exceeded;
		echo $this->simple_auth_lib->error_password_is_wrong;
		echo $this->simple_auth_lib->error_user_is_not_found;







}
}
?>
