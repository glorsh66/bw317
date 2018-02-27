<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mail_test extends CI_Controller {


	public function index()
	{

		//Загружаем либы
		$this->load->library('simple_mail_lib');
		$this->load->model("Usermodel");


		for ($i=0; $i < 100; $i++) {
			$this->simple_mail_lib->send_mail("from@ee.e","to@e.ru","fdf","ed");
		}

echo "amount of processed mails: ". $this->simple_mail_lib->actually_send_mail(1,1) . "<br>";
echo "time elapsed: " . $this->simple_mail_lib->time_elapsed;

}
}
?>
