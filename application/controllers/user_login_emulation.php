<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_login_emulation extends CI_Controller {


	public function index()
	{
		//Загружаем либы
		$this->load->model("Usermodel");
		$this->load->library('session');
		$this->load->helper('cookie');

		//Данные которые пришли с формы
		$user_form = "user1";
		$password_from_form = "test55";

		//Ищем пользователя с таким именем
		$user_data= $this->Usermodel->find_user_exist_and_return_user_data($user_form);
	//Проверям не вернулся ли фалс
	if ($user_data) {
		//Проверям сходится ли пароль
		if (password_verify($password_from_form,$user_data["password"])) {

			//Генерируем временные данные для сессии
			//Наличие одинаковой нам не особо важно - так как у нас
			//любом случае там так же указаны данные пользователя

			//Генерируем абсолютно случайный хэш
			//Мы храним - отдельно селектор для того, что бы исключить возможность обнаружения колличества зарегенных польхователей на сайте
			//Отдельной валидатор.
			//Открытый валидатор мы храним - у пользователя в куках
			//Хэш мы храним в базе. Это нужно для того, что в случае если база пользователей утечет - то злоумышленники не получат доступ ко всем пользователям

			//Do While - для того что бы исключить теоритеческий конфликт если будет сгенеренна одинаковый selector
			do{
				$random_selector = bin2hex(random_bytes(30));
				$random_validator = bin2hex(random_bytes(100));

				$sha256_validator  = hash('sha256', $random_validator);
			 // $hashed_validator = password_hash($random_validator, PASSWORD_DEFAULT);

			} while ($this->Usermodel->find_collision_selecto_session($random_selector));


			//Вставляем сессию в базу данных
			//Мы можем быть уверенны так как она прошла валидацию и исключен факт одинаковых записей
			$this->Usermodel->insert_user_session($random_selector,$sha256_validator, $user_data["id"]);

			//Орпеделяем параметры куки Параметры куки
			$cookie = array(
        'name'   => 'user_selector',
				'value'  => $random_selector,
				'expire' => '5184000'); //Колличество секунд в двух месяцах. В одном - 2592000
			$this->input->set_cookie($cookie);

			$cookie = array(
				'name'   => 'user_validator',
				'value'  => $random_validator,
				'expire' => '5184000'); //Колличество секунд в двух месяцах. В одном - 2592000
			$this->input->set_cookie($cookie);

			//создаем сессию
			$this->session->set_userdata('user_logged_id', $user_data["id"]);
			$this->session->set_userdata('user_logged_name', $user_data["user_name"]);
			$this->session->set_userdata('user_logged_email', $user_data["user_email"]);
			$this->session->set_userdata('user_logged_group_id', $user_data["group_id"]);



//Тестовый блок

$this->db->where('users_sessions_selector',$random_selector);
$this->db->limit(1);
$query = $this->db->get('users_sessions');
//Проверяем нашли ли мы строку вообще
if ($query->num_rows() > 0)
{
	$ret = $query->row_array();
	$test_selector = 	$ret['users_sessions_selector'];
	$test_validator = 	$ret['users_sessions_validator'];
	$test_user_id = $ret['users_sessions_user_id'];
}

//Тестовй блок


echo "Тестовый блок!";
echo "<br>";
echo "Тут выводим данные из базы";
echo "<br>";
echo '$test_selector ' . "len: " . strlen($test_selector) . "   " ."<br>". $test_selector;
echo "<br>";
echo '$test_validator ' . "len: " . strlen($test_validator) . "   " ."<br>". $test_validator;
echo "<br>";
echo '$test_user_id ' . "len: " . strlen($test_user_id) . "   " ."<br>". $test_user_id;
echo "<br>";
echo "Сравнение селектора: " . ($test_selector==$random_selector);
echo "<br>";
echo "Сравнение валидатора без хэша: " . ($test_validator==$random_validator);
echo "<br>";


echo "Сравнение валидатора sha256 хэша: " . hash_equals(hash('sha256', $random_validator),$test_validator);
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";


echo "Данные из кук";
echo "<br>";
echo '$random_selector ' . "len: " . strlen($random_selector) . "   " ."<br>". $random_selector;
echo "<br>";
echo '$random_validator ' . "len: " . strlen($random_validator) . "   " ."<br>". $random_validator;
echo "<br>";
echo '$sha256_validator ' . "len: " . strlen($sha256_validator) . "   " ."<br>". $sha256_validator;
echo "<br>";

//Удаляем старые записи (старше чем 2 месяца)
//Хотя лучше всего это дело перенести в cron
$this->Usermodel->delete_old_sessions();

}// конец иф, если не верный пароль
		else {
//Добавляем инфу о попытке захода пользователя
$this->Usermodel->insert_login_attempts($user_data["id"],$this->input->ip_address());
echo "Пароль не верный";
		}
	} //конец иф, если не найден пользователь
	 else {
		echo "Пользователь не найден";
	}
}
}
?>
