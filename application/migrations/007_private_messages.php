<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_private_messages extends CI_Migration {

    public function up()
    {

        $fields = array(
            'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
                // не работает втроеные ORM он требует обязательно наличия поля id
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),

            'from_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'to_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),

            'lesser_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),

            'greater_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),

            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'COLLATE' => 'utf8_bin',
            ),
            'pm_text' => array(
                'type' =>'VARCHAR',
                'null' => FALSE,
                'constraint' => '21000'
            ),
        );

        $this->dbforge->add_field("`PM_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
        $this->dbforge->add_key(array('lesser_id', 'greater_id'));
        $this->dbforge->create_table('private_messages');

        $this->_addIndexies();

        $this->_addFK();





    }


    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('private_messages',true);
    }



    private function _addIndexies()
    {
        $this->db->query("CREATE INDEX idx_pm_from_timestamp ON private_messages (from_id, PM_timestamp);");
        //TODO: Убрать потом этот индекс, после тестов.
        $this->db->query("CREATE INDEX idx_pm_lesser_greater_id ON private_messages (lesser_id,greater_id,id);");
    }


    private function _addFK()   {

        //TODO: Сделать реальные FOREGEIN keys для alias пользоватеелей
        //
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_from_id_to_siteusers_id` FOREIGN KEY (`from_id`) REFERENCES `site_users` (`id`);");
            $this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_to_id_to_siteusers_id` FOREIGN KEY (`to_id`) REFERENCES `site_users` (`id`);");
            $this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_lesser_id_to_siteusers_id` FOREIGN KEY (`lesser_id`) REFERENCES `site_users` (`id`);");
            $this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_greater_id_to_siteusers_id` FOREIGN KEY (`greater_id`) REFERENCES `site_users` (`id`);");
        }

    }

    private function _dropFK()
    {
        //TODO: Сделать реальные FOREGEIN keys для alias пользоватеелй
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_from_id_to_siteusers_id`;");
            $this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_to_id_to_siteusers_id`;");
            $this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_lesser_id_to_siteusers_id`;");
            $this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_greater_id_to_siteusers_id`;");
        }

    }
}