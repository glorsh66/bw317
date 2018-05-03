<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model3 extends CI_Controller {


	public function index()
	{

	$this->benchmark->mark('start');
	$this->load->library("simple_auth_lib");
	$this->load->model("PMmodel");

	$this->PMmodel->insert_message(2,1,'otvetka');
	$this->PMmodel->insert_message(3,1,'otvetka');
	$this->PMmodel->insert_message(4,1,'otvetka');
	$this->PMmodel->insert_message(5,1,'otvetka');
	$this->PMmodel->insert_message(6,1,'otvetka');
	$this->PMmodel->insert_message(7,1,'otvetka');

	$this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');
    $this->PMmodel->insert_message(1,2,'1 to 2');

    $this->PMmodel->insert_message(2,1,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');
    $this->PMmodel->insert_message(1,2,'2 to 1');

    
	$ar = $this->PMmodel->get_message_thread(1,2);
	for ($i=0;$i<count($ar['messages']);$i++)
    {
      echo $ar['messages'][$i]['pm_text'];
      echo '<br>';

    }
    $this->benchmark->mark('stop');
	echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');


	//var_dump($ar);


	   
	}


}
?>