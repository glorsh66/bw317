<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_users_activation_code extends CI_Migration {

    public function up()
    {

        $fields = array(
            'user_that_will_be_activated_id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
            ),
            'user_activation_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'COLLATE' => 'utf8_bin',
            ),
        );
        $this->dbforge->add_field('`user_activation_code_change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('user_activation_code',TRUE);
        $this->dbforge->add_key('user_that_will_be_activated_id');
        $this->dbforge->create_table('users_activation_code');

        $this->_addFK();

    }

    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('users_activation_code',true); //Удаляем
    }




    private function _addFK()   {
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `users_activation_code` ADD CONSTRAINT `FK_users_activation_code_to_siteusers_id` FOREIGN KEY (`user_that_will_be_activated_id`) REFERENCES `site_users` (`id`);");
        }
    }

    private function _dropFK()
    {
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `users_activation_code` DROP FOREIGN KEY `FK_users_activation_code_to_siteusers_id`;");
        }


    }
}