<?php

class Migrate extends CI_Controller
{

    public function index()
    {

        $this->config->load('migration', TRUE);


       @$current_migration = $this->_getcurrentmigration();
        $migration_version = $this->config->item('migration_version','migration');





        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }


        echo 'Версия миграции из базы данных: ' . $current_migration . "<br>";
        echo 'Версия на которую мигрируем: ' . $migration_version . '<br>';



        if ($migration_version > 1 and $current_migration === 0)
        {
            $this->_populateUserGroups();
            echo  "Создали группы пользователей в таблице user_groups";
        }


    }


    private function _populateUserGroups()
    {
        $this->load->model('Usermodel');
        $this->Usermodel->insert_user_group("admin","The most important guy here period.");
        $this->Usermodel->insert_user_group("moderators","Can delete or edit posts");
        $this->Usermodel->insert_user_group("super_moderators","Can delete or edit posts");
        $this->Usermodel->insert_user_group("users","Just a regual Joe. Nothing special");
        $this->Usermodel->insert_user_group("shop-keepers","Guys who use site quite intensively.");
    }


    private function _getcurrentmigration():int
    {
        if ($this->db->table_exists('migrations')) {
            $row = $this->db->select('version')->get('migrations')->row();
            return $row ? $row->version : '0';
        } else return 0;

    }


}