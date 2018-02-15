<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mail extends CI_Controller {


	public function index()
	{


		$this->load->library('email');

		$this->email->from('openmindedman@yandex.ru', 'Your Name');
		$this->email->to('openmindedman@yandex.ru');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		$this->email->send();



	}
}
?>
