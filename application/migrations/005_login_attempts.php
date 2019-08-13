<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_login_attempts extends CI_Migration {

    public function up()
    {

        $fields = array(
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'COLLATE' => 'utf8_bin',
            ),
            'login_attempts_user_id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
            ),
        );
        // $this->dbforge->add_field('id');
        $this->dbforge->add_field('`login_attempts_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('login_attempts');

        $this->_addFK();

    }

    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('login_attempts',true); //Удаляем
    }




    private function _addFK()   {
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `login_attempts` ADD CONSTRAINT `FK_login_attempts_to_siteusers_id` FOREIGN KEY (`login_attempts_user_id`) REFERENCES `site_users` (`id`);");
        }
    }

    private function _dropFK()
    {
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `login_attempts` DROP FOREIGN KEY `FK_login_attempts_to_siteusers_id`;");
        }


    }
}