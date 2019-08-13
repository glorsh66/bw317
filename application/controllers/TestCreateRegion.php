<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestCreateRegion extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("PMmodel");
        $this->load->model('Usermodel');
        $this->load->library('simple_auth_lib');
        $this->load->helper('url');
        $this->config->set_item('language', 'russian');
    }

    public function index()
    {
        $pathToSql = "SQL/Regions.sql";
        $commands = file_get_contents($pathToSql);
        $lines = file($pathToSql);


        if ($this->db->table_exists('country')) {
            $this->dbforge->drop_table('country', true); //Удаляем если есть
        }
        if ($this->db->table_exists('region')) {
            $this->dbforge->drop_table('region', true); //Удаляем если есть
        }
        if ($this->db->table_exists('city')) {
            $this->dbforge->drop_table('city', true); //Удаляем если есть
        }

        //$this->db->simple_query($commands);

        foreach ($lines as $line) {
            $statement = '';
            $statement .= $line;
            if (substr(trim($line), -1) === ';') {
                $this->db->simple_query($statement);
                $statement = '';
            }
        }
    echo "Базы country, region, city успешно загружены!";


    }

}
?>
