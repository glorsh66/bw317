<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller {


	public function index()
	{
		$this->load->library('simple_auth_lib');
		$this->simple_auth_lib->get_user_data();
		
		echo "<br>";
		echo "This is a controller";
		echo $this->simple_auth_lib->user_name;
		
			
		$this->simple_auth_lib->bar();			
		$this->load->view('Article_view');
		echo("test");
	}
}
?>