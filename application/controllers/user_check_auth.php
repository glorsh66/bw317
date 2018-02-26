<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_check_auth extends CI_Controller {


	public function index()
	{
		//Загружаем либы
		$this->load->model("Usermodel");
		$this->load->model("Usermodel");
		$this->load->model("Usermodel");

		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->model("Usermodel");


		//Временная переменная для тестов
		//Откуда мы взяли id из кук или сессии
		$outkuda = "";
		$test_user_selector = get_cookie('user_selector');
		$test_user_validator = get_cookie('user_validator');



		$user_id = false;
		$user_data=null;


		//Проверям существует ли сессия
		if($this->session->userdata('user_logged_id'))
		{
		$user_id = $this->session->userdata('user_logged_id');
		$outkuda = 'Из сессии';
	  }
		//Проверяем если ли две куки

		elseif(get_cookie('user_selector') && get_cookie('user_validator'))
		{
		$user_selector = get_cookie('user_selector',TRUE);
		$user_validator = get_cookie('user_validator',TRUE);
		$user_id = $this->Usermodel->check_cookie_and_return_id($user_selector,$user_validator);
		$outkuda = 'Из кукисов';
		//Если вернулся false удаляем текущие куки
		//Это могло быть по двум причинам
		//1) Запись удалена так как старая
		//2) Попытка взлома (в даном случае модель убирает запись из базы)
		if (!$user_id) {


			delete_cookie('user_selector');
			delete_cookie('user_validator');
		}
		}

//Проверяем не false ли user_id из сессии или куки
//Если нет значит пользователь не зарегестрирован
if ($user_id)
{
	$user_data = $this->_get_user_data_and_update_last_activity($user_id);
	//Существует ли такой пользователь
	//Если существует то мы обновим сессию на всякий случай
	if ($user_data)
	{
		$this->session->set_userdata('user_logged_id', $user_data["id"]);
		$this->session->set_userdata('user_logged_name', $user_data["user_name"]);
		$this->session->set_userdata('user_logged_email', $user_data["user_email"]);
		$this->session->set_userdata('user_logged_group_id', $user_data["group_id"]);

		echo "Процесс прошел успешно, юзер зашел";
		echo "<br>";
		echo "User id: " . $user_data["id"];
		echo "<br>";
		echo "User name: " . $user_data["user_name"];
		echo "<br>";
		echo "User group_id: " . $user_data["group_id"];
		echo "<br>";
		echo "User last_activity_new " . $user_data["user_last_active_date2"];
		echo "<br>";
		echo "User last_activity_old " . $user_data["user_last_active_date"];
		echo "<br>";
		echo "If = old = new: "; echo  $user_data["user_last_active_date2"] == $user_data["user_last_active_date"] ;
		echo "<br>";
		echo "<br>";
		echo "<br>";
	}
}

echo "User_id " . $user_id . " и получили мы это дело из " . $outkuda;
echo "<br>";
echo "Данные из кук: " . " Валидатор: " . $test_user_validator;
echo "<br>";
echo "Селектор "  . $test_user_selector;
echo "<br>";




echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "Тест моделей";
echo $this->Usermodel->get_int();
$this->load->model("Usermodel");
echo "<br>";
echo $this->Usermodel->get_int();
echo "<br>";
echo "с инкрементом";
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo "<br>";






//Тест либы
$this->load->library("simple_auth_lib");
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "Тест либы";
echo $this->simple_auth_lib->get_int();
$this->load->library("simple_auth_lib");
echo "<br>";
echo $this->simple_auth_lib->get_int();
echo "<br>";
echo "с инкрементом";
echo "<br>";
$this->load->library("simple_auth_lib");
echo $this->simple_auth_lib->get_int_with_increment();
echo "<br>";
echo $this->simple_auth_lib->get_int_with_increment();
echo "<br>";
echo $this->simple_auth_lib->get_int_with_increment();
echo "<br>";
echo "<br>";







echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "Тест моделей";
echo $this->Usermodel->get_int();
$this->load->model("Usermodel");
echo "<br>";
echo $this->Usermodel->get_int();
echo "<br>";
echo "с инкрементом";
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo $this->Usermodel->get_int_with_increment();
echo "<br>";
echo "<br>";


$my_array = array(5 => 'bar',
                  6 => 'foo');
echo $my_array[5];
// Начали транзакцию


$this->db->trans_start();
$this->Usermodel->insert_user_registration_form("user12","user12@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user13","user13@mail.ru","test55");
if ($this->db->trans_status() === FALSE)
{
        $this->db->trans_rollback();
}
else
{
        $this->db->trans_commit();
}




}




//Функция которая возвращает данные юзера
//А также обновляет последнюю активность пользователя в базе данных
private function _get_user_data_and_update_last_activity($input_id)
{
$user_data= $this->Usermodel->find_user_exist_and_return_user_data_by_id($input_id);
//Проверяем не пустой ли массив с юзером вернули.
if ($user_data)
{
//Поскольку мы обновляем это число нам нужно его поменять и в массиве
$user_user_last_active_date = $this->Usermodel->update_user_last_activity($input_id);
$user_data['user_last_active_date2'] = $user_user_last_active_date;
return $user_data;
}
else {
return false;
}
}

}//Конец класса


?>
