<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SPM_lib {

  //Переменные для класса

  const  max_tries_with_incorrect_password = 10;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
                $this->CI->load->helper('url');
                $this->CI->load->helper('cookie');
                $this->CI->load->model('Usermodel');
                $this->CI->load->library('session');
        }



        public function send_pm($from,$to,$message)
        {



        }





}
?>
