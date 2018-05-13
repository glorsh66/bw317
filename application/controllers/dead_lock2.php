<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dead_lock2 extends CI_Controller {
private $pm_table ='private_messages';
private $board_table = 'PM_board';
private $users_table = 'site_users';

	public function index()
	{

$this->benchmark->mark('start');
//$this->db->trans_start();

$tolesser=100;
$togreater=100;
$lesser=6;
$greater=7;
$insertId=66;

        $sql_q = 'INSERT INTO '.$this->board_table.' (lesser_id, greater_id, all_count,lesser_count,greater_count,lesser_unread,greater_unread,last_message)
        VALUES (?, ?, 1,'."$tolesser,$togreater,$tolesser,$togreater,".  $insertId.') ON DUPLICATE KEY UPDATE '.
        "all_count=all_count+1,lesser_count=lesser_count+$tolesser,greater_count=greater_count+$togreater,lesser_unread=lesser_unread+$tolesser,".
        "greater_unread=greater_unread+$togreater,last_message=$insertId;";
        $query = $this->db->query($sql_q,array($lesser,$greater));
$id = $this->db->insert_id();
sleep(10);
//$this->db->trans_rollback();
//$this->db->trans_commit();

$this->benchmark->mark('stop');

echo "Transaction test ";
echo '<br>';
echo "ПОследнйи ID ". $id;
echo '<br>';
echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
echo '<br>';
echo $this->db->last_query();

	}
}
?>