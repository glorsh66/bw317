<?php
class Dogecaptcha extends CI_Controller {

       
       
       
        public function user_registration_captcha()
        {
        	$this->load->library('session');
        	

$im = imagecreatefrompng('img/dogecoin-300.png');
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);
$code = $this->session->registration_capcha_text;
$initial_rast = 80;
for($i = 0; $i < strlen($code); $i++){
	$rand_angle = rand(-30,30);
	$initial_rast=$initial_rast+$i+20;
	imagefttext($im,20,$rand_angle,$initial_rast,100,$fg,'fonts/DogeSans-Regular.otf',$code[$i]);
}
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
}
	
public function _destroy_session()
{
            $this->load->library('session');
            $this->session->unset_userdata('registration_capcha_text');
}


public function change_session()
{
                $this->load->library('session');
                $this->session->registration_capcha_text = strval(rand(10000,99999));
}






public function user_auth_captcha()
{
$this->load->library('session'); 
$im = imagecreatefrompng('img/dogecoin-300.png');
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);
$code = $this->session->user_auth_capcha_text;
$initial_rast = 80;
for($i = 0; $i < strlen($code); $i++){
	$rand_angle = rand(-30,30);
	$initial_rast=$initial_rast+$i+20;
	imagefttext($im,20,$rand_angle,$initial_rast,100,$fg,'fonts/DogeSans-Regular.otf',$code[$i]);
}
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
}
	
public function _user_auth_captcha_destroy()
{
            $this->load->library('session');
            $this->session->unset_userdata('user_auth_capcha_text');
}

public function user_auth_captcha_change_session()
{
$this->load->library('session');
$this->session->user_auth_capcha_text = strval(rand(10000,99999));
}







}
?>