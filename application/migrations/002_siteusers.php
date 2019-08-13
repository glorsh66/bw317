<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_siteusers extends CI_Migration {

    public function up()
    {


        $fields = array(
            'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
                // не работает втроеные ORM он требует обязательно наличия поля id
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE // NOT Null
            ),
            'user_alias' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE, // NOT Null
                'unique' => TRUE
            ),

            'user_name' => array( //Уникальное нужно для создания индекса
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
                'null' => FALSE, // NOT Null
            ),
            'user_email' => array( //Уникальное  нужно для создания индекса
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE,
            ),
            'is_activated_by_mail' => array(
                'type' => 'tinyint',
                'constraint' => '1',
                'unsigned' => TRUE,
                'null' => FALSE, // NOT Null
                'default' => '0',
            ),
            'is_activated_manually' => array(
                'type' => 'tinyint',
                'constraint' => '1',
                'unsigned' => TRUE,
                'null' => FALSE, // NOT Null
                'default' => '0',
            ),

            'ban_reason' => array(
                'type' => 'tinyint',
                'type' =>'VARCHAR',
                'constraint' => '100',
                'null' => TRUE, // maybe null может быть null
            ),
            'is_banned' => array(
                'type' => 'tinyint',
                'constraint' => '1',
                'null' => FALSE, // NOT Null
                'default' => '0',
            ),

            'user_registration_ip' => array(
                'type' =>'VARCHAR',
                'constraint' => '50',
            ),

            'user_registration_ip_if_proxy' => array(
                'type' =>'VARCHAR',
                'constraint' => '50',
            ),


            'user_activation_request' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
            ),
            'user_activation_request' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
            ),
            'user_change_password_request' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
            ),


            'user_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_ip_creation' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),

            'user_ip_last_active' => array(
                'type' =>'VARCHAR',
                'constraint' => '50',
            ),

            'user_last_active_date' => array(
                'type' => 'timestamp',
                'default' => null,
            ),

                'group_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE, // NOT Null
            ),

            'tries_with_wrong_password' => array(
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0,

            ),

            'last_time_wrong_pass_unix_tsmp' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ),

            'blocked_up_to_date' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ),

        );


        $this->dbforge->add_key('id',TRUE); //Делаем ID основным ключем
        $this->dbforge->add_key('user_email'); //создаем индекс для емайла

        $this->dbforge->add_field($fields);
        $this->dbforge->add_field("`password` CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL"); //Добавляем поле для пароля
        $this->dbforge->add_field("`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`blocked_by_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`user_registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");

        $this->dbforge->create_table('site_users');
        //Создаем индексы
        $this->db->query("ALTER TABLE `site_users` DROP INDEX `user_name`, ADD UNIQUE INDEX `user_name` (`user_name`);");
        $this->db->query("ALTER TABLE `site_users` DROP INDEX `user_email`, ADD UNIQUE INDEX `user_email` (`user_email`);");

        //Меняем позиции столбов для лучшей наглядности
        $this->db->query("ALTER TABLE `site_users` CHANGE COLUMN password password CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER id;");
        $this->db->query("ALTER TABLE `site_users` CHANGE COLUMN `modified` `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER group_id;");

        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $this->_addFK();



    }

    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('site_users');
    }



    private function _addFK()
    {
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `site_users` ADD CONSTRAINT `FK_site_users_user_groups` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`);");
        }
    }

    private function _dropFK() {
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `site_users` DROP FOREIGN KEY `FK_site_users_user_groups`;");
        }

    }







}