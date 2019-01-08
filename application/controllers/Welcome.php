<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		$this->load->helper('url');
		$path    = './application/controllers';
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		foreach ($files as &$value) {
    $value = substr($value, 0, -4);
		}


		$data['files'] = $files;
		$data['title'] = "Ссылки на все контроллеры";
		$data['heading'] = "Ссылочки на контроллеры";

		$this->load->view('service_view', $data);



	}
}
