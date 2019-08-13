<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_users_sessions extends CI_Migration {

    public function up()
    {


        $fields = array(
            'users_sessions_selector' => array(
                'type' => 'VARCHAR',
                'constraint' => '60',
                'COLLATE' => 'utf8_bin',
            ),
            'users_sessions_validator' => array(
                'type' => 'VARCHAR',
                'constraint' => '64',
            ),
            'users_sessions_user_id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
            ),

        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_field("`users_sessions_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('users_sessions_selector',TRUE);//Делаем ID основным ключем
        $this->dbforge->create_table('users_sessions');
        $this->_addFK();

    }

    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('users_sessions',true); //Удаляем
    }




    private function _addFK()   {
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `users_sessions` ADD CONSTRAINT `FK_usersessions_to_siteusers_id` FOREIGN KEY (`users_sessions_user_id`) REFERENCES `site_users` (`id`);");
        }
    }

    private function _dropFK()
    {
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `users_sessions` DROP FOREIGN KEY `FK_usersessions_to_siteusers_id`;");
        }


    }
}