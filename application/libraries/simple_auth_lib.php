<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class simple_auth_lib {

        protected $CI;
        protected $iflogginned;
        public $user_id;
        public $user_name;
        public $user_group;


        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
                $this->CI->load->helper('url');
                $this->CI->load->model('Usermodel');
        }




        public function user_login($user_name_or_email,$password)
        {

        $user_q= $this->CI->Usermodel->find_user_whith_pass_exist_and_return_all_data($user_name_or_email,$password);


        if ($user_q)
        {
		return $user_q->id;
		}
		else
		{



		return FALSE;


		}


		}







       public function get_class_data()
       {

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
