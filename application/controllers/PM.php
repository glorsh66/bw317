git<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PM extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("PMmodel");
        $this->load->model('Usermodel');

    }

    public function index()
    {
       // $this->send_pm();
        $this->show_pm_board();
    }




    public function send_pm()
    {
        $me = 2;
        $to = 1;

        for ($i =0; $i <15; $i++)
        $this->PMmodel->insert_message($me, $to,"Тест: " . " From: " . $me . " To: " .$to );
    }


    public function show_pm_board()
    {
        $me = 1;
        $mess_board = $this->PMmodel->get_board($me);
        if ($mess_board===FALSE)
        {
            //TODO: Убрать во вьюшку
            echo "У Вас еще нет никаких сообщений";
        }
        else
        {

                /**
                 * Функция возвращает борду пользователей
                 * Возможно с лимитом на число выводимых строк и оффсетом (для пагинации)
                 * На выходе возвращает либо массив строк, либо FALSE если у пользователя еще нет никаких активных переписок.
                 * Возвращает массив ['lesser_id'] ['greater_id'] ['all_count'] ['lesser_count'] ['greater_count'] ['lesser_unread']
                 * ['greater_unread'] ['last_message'] ['from_id'] ['to_id'] ['PM_timestamp'] ['has_been_read'] ['pm_text']
                 * ['user_name'] ['user_last_active_date'] ['isactivated']
                 **/
            //TODO: Убрать во вьюшку
            foreach ($mess_board as $ms)
            echo "lesser_id: ". $ms['lesser_id']
                 . " lesser_user_name: " . $ms['lesser_user_name'] .
                " greater_id: " . $ms['greater_id'] . " greater_user_name: " . $ms['greater_user_name'].
                " all_count: " . $ms['all_count']  . "<br>";


            //Делаем красивое представление ключей
            $ar_k = array_keys($mess_board[0]);
            $ar_res = array_map(function ($v){return "['{$v}']";},$ar_k);
            foreach($ar_res as $r)
                echo $r.' ';

        }


    }




}
?>
