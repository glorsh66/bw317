<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_person extends CI_Migration {

    public function up()
    {


        //Создаем  таблицу person

        $fields = array(
            'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
                // не работает втроеные ORM он требует обязательно наличия поля id
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => FALSE // Так, как это будет ID пользователя и таблица будет жестко привязанна
            ),
            'person_alias' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE, // NOT Null
                'unique' => TRUE
            ),
            'registration_date' => array('type' => 'timestamp'),
            'last_active_date' => array('type' => 'timestamp','default' => null ),
            'profile_image' => array('type' => 'VARCHAR', 'constraint' => 255),
            'amount_of_images' => array('type' => 'TINYINT'),
            'short_text' => array('type' => 'VARCHAR', 'constraint' => 255)
        );


        $this->dbforge->add_field($fields);
        $this->dbforge->add_field("`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");



        //Создаем все поля из PERSONMODEL (Автоконфигурация)
        //TODO: Сделать возможность добавлять текстовые поля,которые будут храниться в JSON
        $this->load->model('Personmodel');
        $this->Personmodel->initialize(Personmodel::new);
        $this->Personmodel->makeAllMysqlFields(); //Создаем поля из PERSONMODEL




        $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
        $this->dbforge->create_table('person');
        $this->db->query("ALTER TABLE `person` CHANGE COLUMN `registration_date` `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER short_text;");
        $this->db->query("ALTER TABLE `person` CHANGE COLUMN `last_active_date` `last_active_date` timestamp NOT NULL AFTER short_text;");


        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $this->_addFK();




    }

    public function down()
    {
        $this->_dropFK();
        $this->dbforge->drop_table('person',true); //Удаляем если есть такая таблица
    }



    private function _addFK()   {
        //Добавляем FOREIGN ключи (Работаем если только у нас в конфигурации написанно про использование ключей)
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE)
        {
            $this->db->query("ALTER TABLE `person` ADD CONSTRAINT `FK_site_users_id_to_person_id` FOREIGN KEY (`id`) REFERENCES `site_users` (`id`);");
        }
    }

    private function _dropFK()  {
        //Дропаем FOREIGN key
        $useFk = $this->config->item('migration_install_indexes_and_FK');
        if ($useFk === TRUE) {
            $this->db->query("ALTER TABLE `person` DROP FOREIGN KEY `FK_site_users_id_to_person_id`;");
    }




    }
    }