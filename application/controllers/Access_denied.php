<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_denied extends CI_Controller {


	public function index()
	{
	$this->load->view('Access_denied_view');
	}
}
?>