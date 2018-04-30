<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sha256_test extends CI_Controller {


	public function index()
	{



// Some code happens here



$time_start = microtime(true);

$random_hash = bin2hex(random_bytes(100));
$str = $random_hash;   // Строка, хэш которой требуется
$sha256 = hash('sha256', $str);   // Хэш по алгоритму sha256
$sha256_2 = hash('sha256', $str);   // Хэш по алгоритму sha256
$sha512 = hash('sha512', $str);   // Хэш по алгоритму sha512




echo "<br>";
echo "random_hash " ."Длинна строки ". strlen($random_hash) . "  " .$random_hash ;
echo "<br>";
echo "sha256 " ."Длинна строки " . strlen($sha256)  ;
echo "<br>";
echo "sha256_2 "."Длинна строки " . strlen($sha256_2) ;
echo "<br>";
echo "sha512 "."Длинна строки " . strlen($sha512) ;
echo "<br>";
echo "Hash equals " . hash_equals($sha256,$sha256_2);
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
