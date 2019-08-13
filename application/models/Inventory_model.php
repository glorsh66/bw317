<?php
class Inventory_model extends CI_Model {

    public $title;
    public $content;
    public $date;

    public function get_category_list()
    {

        $a[] = (object)array('id' => 1, 'name'=>'sd',);
        return  $a;


    }

    public function get_category_name()
    {

    }


    public function get_category_name2()
    {
echo  "sda";
    }
}