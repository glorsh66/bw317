<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mail extends CI_Controller {


	public function index()
	{
$account="H̡̫̤̤̣͉̤ͭ̓̓̇͗̎̀ơ̯̗̱̘̮͒̄̀̈ͤ̀͡w͓̲͙͖̥͉̹͋ͬ̊ͦ̂̀̚ ͎͉͖̌ͯͅͅd̳̘̿̃̔̏ͣ͂̉̕ŏ̖̙͋ͤ̊͗̓͟͜e͈͕̯̮̙̣͓͌ͭ̍̐̃͒s͙͔̺͇̗̱̿̊̇͞ ̸̤͓̞̱̫ͩͩ͑̋̀ͮͥͦ̊Z̆̊͊҉҉̠̱̦̩͕ą̟̹͈̺̹̋̅ͯĺ̡̘̹̻̩̩͋͘g̪͚͗ͬ͒o̢̖͇̬͍͇͓̔͋͊̓ ̢͈͙͂ͣ̏̿͐͂ͯ͠t̛͓̖̻̲ͤ̈ͣ͝e͋̄ͬ̽͜҉͚̭͇ͅx͎̬̠͇̌ͤ̓̂̓͐͐́͋͡ț̗̹̝̄̌̀ͧͩ̕͢ ̮̗̩̳̱̾w͎̭̤͍͇̰̄͗ͭ̃͗ͮ̐o̢̯̻̰̼͕̾ͣͬ̽̔̍͟ͅr̢̪͙͍̠̀ͅǩ̵̶̗̮̮ͪ́?̙͉̥̬͙̟̮͕ͤ̌͗ͩ̕͡ ";
$symb_array = str_split($account);
$symb_array = array_map(function($symb){return bin2hex($symb);}, $symb_array);
$len = count($symb_array);
for ($i = 0; $i < $len; $i++){
    if ($symb_array[$i] === 'cc' || $symb_array[$i] === 'cd' || $symb_array[$i] === 'd2'){
        unset($symb_array[$i]);
        if (isset ($symb_array[++$i])){
            unset($symb_array[$i]);
        }
    }
}

var_dump($symb_array);



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
