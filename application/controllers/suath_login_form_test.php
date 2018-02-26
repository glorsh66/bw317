<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class suath_login_form_test extends CI_Controller {


	public function index()
	{

		//Загружаем либы
		$this->load->library('simple_auth_lib');
		$this->load->model("Usermodel");

		$user_name_or_email_form = "user2";
		$password_from_form = "test55";

		$otvet_1=FALSE;
		$otvet_1  = $this->simple_auth_lib->user_login_from_form($user_name_or_email_form
		,$password_from_form ,1);

		$this->session->unset_userdata('user_logged_id');
		$this->session->unset_userdata('user_logged_name');
		$this->session->unset_userdata('user_logged_email');
		$this->session->unset_userdata('user_logged_group_id');
		$this->session->sess_destroy();

		$otvet_2=FALSE;
		//$otvet_2 = $this->simple_auth_lib->check_if_user_is_loggined();

    //echo "First_otvet: " . $otvet_1;
		echo $this->simple_auth_lib->error_user_name_or_email_form_is_empty;
		echo $this->simple_auth_lib->error_password_from_form;
		echo $this->simple_auth_lib->error_limit_for_login_attemts_with_wrong_password_is_exceeded;
		echo $this->simple_auth_lib->error_password_is_wrong;
		echo $this->simple_auth_lib->error_user_is_not_found;



		//check_model

		$user_selector = "'Sarah; ''''DELETE FROM employees;";

		$this->db->where('users_sessions_selector',$user_selector);
		$this->db->limit(1);
		$otvet_3 = $this->db->get_compiled_delete('user_selector');

		echo "otvet_1: ".  $otvet_1. '<br>';
		echo "otvet_2: ".  $otvet_2. '<br>';
		echo "otvet_3: ".  $otvet_3. '<br>';
		echo "Logging_function: " . $otvet_2 . '<br>';
		echo "Logging_function: " . $otvet_2 . '<br>';
		echo 'is_user_loggined: ' . $this->simple_auth_lib->is_user_loggined.'<br>';
		echo "logged_otkuda: " . $this->simple_auth_lib->logged_otkuda . '<br>';

		if ($this->simple_auth_lib->is_user_loggined) {
		echo "User_name: " .  $this->simple_auth_lib->user_data['user_name'];
		}











}
}
?>
