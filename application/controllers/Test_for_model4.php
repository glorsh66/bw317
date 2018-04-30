<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model4 extends CI_Controller {


	public function index()
	{


 $sql="DROP PROCEDURE IF EXISTS GetAllProducts";
     $this->db->query($sql);
$sql="CREATE DEFINER=`root`@`%` PROCEDURE `sp_user_login`(
  IN loc_username VARCHAR(255),
  IN loc_password VARCHAR(255)
)
BEGIN

  SELECT user_id,
         user_name,
         user_emailid,
         user_profileimage,
         last_update
    FROM tbl_user
   WHERE user_name = loc_username
     AND password = loc_password
     AND status = 1;

END";
$this->db->query($sql);

$sql='DROP PROCEDURE IF EXISTS GetAllProducts; CREATE PROCEDURE GetAllProducts()
   BEGIN
   SELECT *  FROM private_messages;
   END';

echo $this->db->simple_query($sql);

	}


}
?>