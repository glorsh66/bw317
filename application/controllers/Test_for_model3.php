<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_for_model3 extends CI_Controller {


	public function index()
	{

	$this->benchmark->mark('start');
	$this->load->library("simple_auth_lib");
	$this->load->model("PMmodel");
	$this->load->model('Usermodel');

//	$this->PMmodel->insert_message(2,1,'otvetka');
//	$this->PMmodel->insert_message(3,1,'otvetka');
//	$this->PMmodel->insert_message(4,1,'otvetka');
//	$this->PMmodel->insert_message(5,1,'otvetka');
//	$this->PMmodel->insert_message(6,1,'otvetka');
//	$this->PMmodel->insert_message(7,1,'otvetka');
//
//	$this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//    $this->PMmodel->insert_message(1,2,'1 to 2');
//
//    $this->PMmodel->insert_message(2,1,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
////    $this->PMmodel->insert_message(1,2,'2 to 1');
//    $this->PMmodel->insert_message(1,2,'2 to 1');
//        for ($i=0;$i<100000;$i++) {
//            $this->PMmodel->insert_message(5, 6, '5 to 6');
//        }

        $this->PMmodel->insert_message(5,6,'5 to 6');
        $this->PMmodel->insert_message(6,5,'6 to 5');
    $owner = 6;
    $second =5;
        //Берем сообщения
	$ar = $this->PMmodel->get_message_thread($owner,$second,10,100001);

        var_dump($this->Usermodel->find_user_exist_and_return_user_data_by_id($second)['user_name']);

	echo "Owner is: " . $owner. " name: " . $this->Usermodel->find_user_exist_and_return_user_data_by_id($owner)['user_name'];
	echo '<br>';
	echo 'second users is: ' . $second . " name: " . $this->Usermodel->find_user_exist_and_return_user_data_by_id($second)['user_name'];
	echo '<br>';



	echo "<style>td{ text-align: center;}</style>";
	echo "<table border=\"1\" align='left'><tr>
         <td>lesser_id</td>".
        '<td>greater_id</td>'.
        '<td>all_count</td>'.
        '<td>lesser_count</td>'.
        '<td>greater_count</td>'.
        '<td>lesser_unread</td>'.
        '<td>greater_unread</td>'.
        '<td>last_message</td>'.
        '<td>Новых сообщений</td>'.
        '</tr>';


	echo '<tr>'.
    "<td>{$ar['board']['lesser_id']}</td>".
    "<td>{$ar['board']['greater_id']}</td>".
    "<td>{$ar['board']['all_count']}</td>".
    "<td>{$ar['board']['lesser_count']}</td>".
    "<td>{$ar['board']['greater_count']}</td>".
    "<td>{$ar['board']['lesser_unread']}</td>".
    "<td>{$ar['board']['greater_unread']}</td>".
    "<td>{$ar['board']['last_message']}</td>".
    "<td>{$ar['board']['lesser_unread']}</td>"
    .'</tr>'
    .'</table>';




	echo "<br>";
	echo "<table border=\"1\" align='left'><tr>";
	echo "<td>id</td>".
         "<td>has_been_read</td>".
         "<td>PM_timestamp</td>".
         "<td>from_id</td>".
         "<td>to_id</td>".
         "<td>lesser_id</td>".
         "<td>greater_id</td>".
         "<td>ip_address</td>".
         "<td>Новое или непрочитанное</td>".
        "<td>pm_text</td>".
        "</tr>";


	for ($i=0;$i<count($ar['messages']);$i++)
    {
        $new_or_read="Пусто";
        if (((int)$ar['messages'][$i]['has_been_read'])===0)
            $new_or_read = ((int)$ar['messages'][$i]['to_id']) === $owner?'Новое':'Непрочитанное';

      echo "<tr>".
           "<td>{$ar['messages'][$i]['id']}</td>".
           "<td>{$ar['messages'][$i]['has_been_read']}</td>".
           "<td>{$ar['messages'][$i]['PM_timestamp']}</td>".
           "<td>{$ar['messages'][$i]['from_id']}</td>".
           "<td>{$ar['messages'][$i]['to_id']}</td>".
           "<td>{$ar['messages'][$i]['lesser_id']}</td>".
           "<td>{$ar['messages'][$i]['greater_id']}</td>".
           "<td>{$ar['messages'][$i]['ip_address']}</td>".
           "<td>{$new_or_read}</td>".
           "<td>{$ar['messages'][$i]['pm_text']}</td>".
           "</tr>";
    }

    echo "</table><br>";
      "<td>pm_text</td>".
    $this->benchmark->mark('stop');


      echo "всего сообщений для пользователя {$owner}: ". $this->PMmodel->count_all_messages($second);

      echo '<br>';
      echo "всего непрочитанных для пользователя {$owner}: ". $this->PMmodel->count_all_unred_messages($owner);

        echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
	echo '<br>';

    $ar_k = array_keys($ar['messages'][0]);
    $ar_res = array_map(function ($v){return "['{$v}']";},$ar_k);
    foreach($ar_res as $r)
    echo $r.' ';

    foreach($ar['q'] as $r)
            echo '<br>'.$r.'<br>';


	//var_dump($ar);
	}







}
?>