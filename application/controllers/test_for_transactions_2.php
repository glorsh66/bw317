<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test_for_transactions_2 extends CI_Controller {


	public function index()
	{

$this->benchmark->mark('start');
$this->db->trans_start();
$query =  $this->db->where('lesser_id',1)->where('greater_id',3)->get('PM_board');
$ar= $query->result_array();

//$this->db->set('all_count',$ar[0]['all_count']+1)->where('lesser_id',1)->where('greater_id',3)->update('PM_board');
$this->db->set('all_count','all_count+10',FALSE)->where('lesser_id',1)->where('greater_id',3)->update('PM_board');
$this->db->trans_commit();

$this->benchmark->mark('stop');

echo "Transaction test ";
echo '<br>';
echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
echo '<br>';
echo $this->db->last_query();

	}
}
?>