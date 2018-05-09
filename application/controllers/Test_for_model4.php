<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model4 extends CI_Controller {


	public function index()
	{
    $this->benchmark->mark('start');
    $this->load->model("PMmodel");
    $ar = $this->PMmodel->get_board(5);
    var_dump($ar);
    echo '<br>';

    //Делаем красивое представление ключей
    $ar_k = array_keys($ar[0]);
    $ar_res = array_map(function ($v){return "['{$v}']";},$ar_k);
    foreach($ar_res as $r)
    echo $r.' ';


    echo '<br>';

    echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');

	}
}
?>