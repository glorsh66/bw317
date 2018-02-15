<?php
class cli extends CI_Controller {

        public function message($to = 'World')
        {
                echo "Hello {$to}!".PHP_EOL;
        }
}
?>
