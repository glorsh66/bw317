<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class big_number_test extends CI_Controller {


	public function index()
	{


$time_start = microtime(true);

$a = '0.000000000000000000000000000000000000000000000000000000004';
$b = '9.999999999999999999999999999999999999999999999999999999995';
echo "<br>";
echo bcadd($a, $b);     // 6
echo "<br>";
echo bcadd($a, $b, strlen($b)-2);  // 6.2340
echo "<br>";



$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Ничего не делал $time секунд\n";

echo "<br>";
//$mulw = gmp_mul("12345678", "2000");
//echo gmp_strval($mulw);




	}
}
?>
