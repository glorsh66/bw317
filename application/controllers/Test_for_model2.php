<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model2 extends CI_Controller {



private function _check_user($id)
{
	//Создаем массивчик дяя возврата функции
	$ret_arr['usr_data'] =FALSE;
	$ret_arr['cause'] ="Not defined";
	$ret_arr['allowed'] =FALSE;


	$user_data= $this->Usermodel->find_user_exist_and_return_user_data("user1");

	//Проверям если не фалс
	if ($user_data) {
		//Проверяем не забанен ли юзерок
		if (intval($user_data['ibanned']) == 0) {

			$ret_arr['allowed'] =true;
			$ret_arr['cause'] ="All righty";
			$ret_arr['usr_data'] = $user_data;
			return 	$ret_arr;
		}
		else {
			$ret_arr['cause'] ="User has been banned";
			return 	$ret_arr;
		}

	}else
	{
		$ret_arr['cause'] ="User has not been found";
		return 	$ret_arr;
	}
	return 	$ret_arr;
}



	public function index()
	{
		//Load libs
		$this->load->model("Usermodel");
		$this->load->model("Session_model");
		$this->load->library('session');

	  $password = "test55";


		//Шаг 1 - проверям сессиию
		//Шаг 2 - проверям куку
		//Шаг 3 - проверям ввод ручной

		//На каждом шаге проверям, что пользователь существует и что он не в бане
		//Если неправильно введен пароль - то пишем в таблицу неудачных заходов

		//Если существуют сессии
		$temp1 = $this->session->has_userdata('user_logged_id');
		$temp2 = $this->session->has_userdata('user_logged_name');
		$temp3 = $this->session->has_userdata('user_logged_email');
		$temp4 = $this->session->has_userdata('user_logged_group_id');





		if ($this->session->has_userdata('user_logged_id') && $this->session->has_userdata('user_logged_name')
		&& $this->session->has_userdata('user_logged_name') &&
		$this->session->has_userdata('user_logged_group_id'))
		{

			$usr_id_temp = intval($this->session->userdata('user_logged_id'));
			echo "User id ". 	$usr_id_temp;

			$user_data_temp = $this->_check_user($usr_id_temp);

			$user_data = $user_data_temp['usr_data'];
			echo $user_data["user_name"];
			echo "<br>";
			echo $user_data_temp['cause'];
			echo "<br>";

			echo "<br>";

			echo $user_data["user_name"];
		}

		echo "<br>";
		echo $temp1;
		echo "<br>";
		echo $temp2;
		echo "<br>";
		echo $temp3;
		echo "<br>";
		echo $temp4;
		echo "<br>";

}
}
?>
