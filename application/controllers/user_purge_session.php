<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_purge_session extends CI_Controller {


	public function index()
	{

		//Загружаем либы
		$this->load->model("Usermodel");
		$this->load->library('session');
		$this->load->helper('cookie');

		//Удаляем данные сессии
		$this->session->unset_userdata('user_logged_id');
		$this->session->unset_userdata('user_logged_name');
		$this->session->unset_userdata('user_logged_email');
		$this->session->unset_userdata('user_logged_group_id');
		$this->session->sess_destroy();


		echo "Всё удалии". "<br>";





}
}
?>
