<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_user_groups extends CI_Migration {

    public function up()
    {


        //Создаем  таблицу person

        $fields = array(
            'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
                // не работает втроеные ORM он требует обязательно наличия поля id
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE

            ),
            'groups_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'groups_description' => array(
                'type' =>'VARCHAR',
                'constraint' => '255',
            ));


        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
        $this->dbforge->create_table('user_groups');


    }

    public function down()
    {
        $this->dbforge->drop_table('user_groups',true); //Удаляем есои есть
    }
}