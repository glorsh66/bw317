<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PM_board extends CI_Migration {

    public function up()
    {

        $fields = array(
            'lesser_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'greater_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'all_count' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'lesser_count' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'greater_count' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'lesser_unread' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'greater_unread' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'last_message' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'lesser_last_read' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'greater_last_read' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'short_last_message' => array( //Текст последнего сообщения
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE, // NOT Null
            ),
            'sha1_last_message' => array( //Хэш текста сообщения + id пользователя (для того что бы не считать одинаковым сообщение от разных пользователей.).
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => FALSE, // NOT Null
            ),
            'count_same_message' => array( //Колличество одинаковых сообщений которые введены подряд.
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'last_time_sendmessage_in_n_minutes_lesser' => array(//Служит для того что отслеживать злостных спамеров. Обновлять после 5 (или N) минут
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ),
            'last_time_sendmessage_in_n_minutes_greater' => array(//Служит для того что отслеживать злостных спамеров. Обновлять после 5 (или N) минут
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ),
            'count_messages_in_n_minutes_lesser' => array(//Сколько было отосланно сообщений за 5 или (N) минут.
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0,
            ),
            'count_messages_in_n_minutes_greater' => array(//Сколько было отосланно сообщений за 5 или (N) минут.
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0,
            ),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key(array('lesser_id', 'greater_id'),true);
        $this->dbforge->create_table('PM_board');

        $this->_addIndexies();


    }


    public function down()
    {
        $this->dbforge->drop_table('PM_board',true);
    }



    private function _addIndexies()
    {

    }


    private function _addFK()   {

        //TODO: Сделать реальные FOREGEIN keys для alias пользоватеелй
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            //FOREIGN keys to users
            $this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_lesser_id_to_siteusers_id` FOREIGN KEY (`lesser_id`) REFERENCES `site_users` (`id`);");
            $this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_greater_id_to_siteusers_id` FOREIGN KEY (`greater_id`) REFERENCES `site_users` (`id`);");

            //Foreign kesys to messages
            $this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_lesser_last_read_to_private_messages_id` FOREIGN KEY (`lesser_last_read`) REFERENCES `private_messages` (`id`);");
            $this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_greater_last_read_to_private_messages_id` FOREIGN KEY (`greater_last_read`) REFERENCES `private_messages` (`id`);");
            $this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_last_message_to_private_messages_id` FOREIGN KEY (`last_message`) REFERENCES `private_messages` (`id`);");
        }

    }

    private function _dropFK()
    {
        //TODO: Сделать реальные FOREGEIN keys для alias пользоватеелй
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_lesser_id_to_siteusers_id`;");
            $this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_greater_id_to_siteusers_id`;");
            $this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_lesser_last_read_to_private_messages_id`;");
            $this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_greater_last_read_to_private_messages_id`;");
            $this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_last_message_to_private_messages_id`;");
        }

    }
}