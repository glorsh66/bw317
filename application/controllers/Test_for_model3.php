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
//            $this->PMmodel->insert_message(3, 1, '3 to 1');
//            $this->PMmodel->insert_message(4, 2, '4 to 2');
//            $this->PMmodel->insert_message(5, 4, '5 to 4');
//            $this->PMmodel->insert_message(5, 4, '5 to 4');
//            $this->PMmodel->insert_message(5, 2, '5 to 2');
//            $this->PMmodel->insert_message(5, 2, '5 to 2');
//        }
//////
//                for ($i=0;$i<10;$i++) {
//            $this->PMmodel->insert_message(2, 4, '3 to 1');
//            $this->PMmodel->insert_message(4, 2, '4 to 2');
//            $this->PMmodel->insert_message(2, 5, '3 to 1');
//            $this->PMmodel->insert_message(5, 2, '4 to 2');
//            $this->PMmodel->insert_message(5, 2, '4 to 2');
//        }


//$this->benchmark->mark('start');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//    echo '<br>'. $this->db->last_query();
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->PMmodel->insert_message(2, 5, '3 to 1');
//$this->PMmodel->insert_message(5, 2, '4 to 2');
//$this->benchmark->mark('stop');
echo '<br>';
echo "Elapsed time: INSERT" . $this->benchmark->elapsed_time('start','stop');

echo '<br>';
    $owner = 5;
    $second =2;
        //Берем сообщения

     	$this->benchmark->mark('start');
	$ar = $this->PMmodel->get_message_thread($owner,$second,10,100);

	 $this->benchmark->mark('stop');
	 echo "Elapsed time:  GET THREAD" . $this->benchmark->elapsed_time('start','stop');


	 $this->benchmark->mark('start');
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
        '<td>lesser_last_read</td>'.
        '<td>greater_last_read</td>'.
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
    "<td>{$ar['board']['lesser_unread']}</td>".
    "<td>{$ar['board']['lesser_last_read']}</td>".
    "<td>{$ar['board']['greater_last_read']}</td>"
    .'</tr>'
    .'</table>';




	echo "<br>";
	echo "<table border=\"1\" align='left'><tr>";
	echo "<td>id</td>".
    //     "<td>Было ли прочитанно</td>".
         "<td>PM_timestamp</td>".
         "<td>from_id</td>".
         "<td>to_id</td>".
         "<td>lesser_id</td>".
         "<td>greater_id</td>".
         "<td>ip_address</td>".
         "<td>Новое или непрочитанное</td>".
        "<td>pm_text</td>".
        "</tr>";


	//Проверям на лессер or greater
    $lesser = min($owner,$second);
    $greater = max($owner,$second);
    $owner_is_lesser = $lesser===$owner?TRUE:FALSE;
    $lesser_last_read =  (int)$ar['board']['lesser_last_read'];
    $greater_last_read=  (int)$ar['board']['greater_last_read'];


	for ($i=0;$i<count($ar['messages']);$i++)
    {
        $new_or_read="Пусто";
        $msg_id = (int)$ar['messages'][$i]['id'];

        if ( ((int)$ar['messages'][$i]['to_id'])===$owner )
        {
            if ($owner_is_lesser)
            {
                if ($msg_id>$lesser_last_read) $new_or_read= 'Новое сообщение';
            }else
            {
                if ($msg_id>$greater_last_read) $new_or_read= 'Новое сообщение';
            }
        }
        else
        {
             if ($owner_is_lesser)
            {
                if ($msg_id>$greater_last_read) $new_or_read= 'Непрочитанное';
            }else
            {
                if ($msg_id>$lesser_last_read) $new_or_read= 'Непрочитанное';
            }
        }



      echo "<tr>".
           "<td>{$ar['messages'][$i]['id']}</td>".
 //          "<td>{$ar['messages'][$i]['has_been_read']}</td>".
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




    //  echo "всего сообщений для пользователя {$owner}: ". $this->PMmodel->count_all_messages($second);

      echo '<br>';
   //   echo "всего непрочитанных для пользователя {$owner}: ". $this->PMmodel->count_all_unred_messages($owner);


	echo '<br>';

    $ar_k = array_keys($ar['messages'][0]);
    $ar_res = array_map(function ($v){return "['{$v}']";},$ar_k);
    foreach($ar_res as $r)
    echo $r.' ';

    foreach($ar['q'] as $r)
            echo '<br>'.$r.'<br>';

    $this->benchmark->mark('stop');
     echo '<br>';
	 echo "Elapsed time for render: " . $this->benchmark->elapsed_time('start','stop');
	  echo '<br>';



    $this->benchmark->mark('start');
    echo '<br>';
    echo "Всего сообщений: " . $this->PMmodel->count_all_messages($owner,$second);
    echo '<br>'. $this->db->last_query();
    $this->benchmark->mark('stop');
    echo '<br>';
	echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
    echo '<br>';



    $this->benchmark->mark('start');
    echo '<br>';
    $minutes = 30;
    echo "Всего сообщений " . $this->PMmodel->count_ammount_of_sended_in_x_minutes(11,10) . " за период в {$minutes} мин. ";
    echo '<br>'. $this->db->last_query();
    $this->benchmark->mark('stop');
    echo '<br>';
	echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
    echo '<br>';



    $this->benchmark->mark('start');
    echo '<br>';
    $minutes = 30;
    $ret = $this->PMmodel->count_all_new_messages_from_board(12);
    if (is_array($ret))
    {
        // ['unread']['messages']['all_messages']['threads']['unread_threads']
         echo '<br>';
         echo '<br>'."Всего непрочитанных сообщений: " . $ret['unread'];
         echo '<br>'."Всего сообщений для пользователя: " . $ret['messages'];
         echo '<br>'."Всего всего сообщений в общем: " . $ret['all_messages'];
         echo '<br>'."Всего всего переписок: " . $ret['threads'];
         echo '<br>'."Всего всего переписок с непрочитанными сообщениями: " . $ret['unread_threads'];
    }
    echo '<br>'. $this->db->last_query();
    $this->benchmark->mark('stop');
    echo '<br>';
	echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');
    echo '<br>';



    echo substr('вфвыфвфывфвфывфыв',0,11);

	}







}
?>