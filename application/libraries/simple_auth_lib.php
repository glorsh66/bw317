<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class simple_auth_lib {

  private static $test_int = 1;
  public $glob_var=0;
        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
                $this->CI->load->helper('url');
                $this->CI->load->model('Usermodel');
                $this->glob_var++;
                SELF::$test_int++;

        }


        public function get_int()
        {
          return SELF::$test_int;
        }

        public function get_int_with_increment()
        {
          return SELF::$test_int++;
        }










        public function get_user_data()
        {
             $this->CI->load->model('Usermodel');
         // $arr =  $this->CI->Usermodel->get_first_user();

           $bool =  $this->CI->Usermodel->get_first_user();

            $this->user_id   = intval($bool['id']);
            $this->user_name = $bool['user_name'];



            echo "<br>";
            echo "It's a lib";
            echo "<br>";
            echo $bool['user_name'];
			echo "<br>";
			echo $bool['id'];
			echo "<br>";
			echo $bool['user_email'];
			echo "<br>";


        }

        public function bar()
        {
          if ($this->iflogginned)
          {
		  	    echo("<br>");
          echo("bar");

		  } else{
		  	redirect('Access_denied', 'refresh');
        //die();
		  }


        }

}
?>
