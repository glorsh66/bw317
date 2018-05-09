<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model4 extends CI_Controller {


	public function index()
	{


	        $this->benchmark->mark('start');
	    	$this->load->model("PMmodel");
	    	var_dump($this->PMmodel->get_board(10));
	    	echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');

	}


}
?>