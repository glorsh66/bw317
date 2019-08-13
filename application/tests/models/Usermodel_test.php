<?php

class Usermodel_test extends TestCase
{
    public function setUp()
    {

        $this->resetInstance();
        $this->CI->load->library('migration');
        $this->CI->migration->version(0);
        $this->CI->migration->version(8);
        $this->CI->load->model('Usermodel');
    }

    public function test_insert_user_group()
    {
        $this->CI->Usermodel->insert_user_group("admin","The most important guy here period.");
        $this->CI->Usermodel->insert_user_group("moderators","Can delete or edit posts");
        $this->CI->Usermodel->insert_user_group("super_moderators","Can delete or edit posts");
        $this->CI->Usermodel->insert_user_group("users","Just a regual Joe. Nothing special");
        $this->CI->Usermodel->insert_user_group("shop-keepers","Guys who use site quite intensively.");
        $this->CI->db->where('groups_name','admin');
        $result_int = $this->CI->db->count_all_results('user_groups');
        $this->assertGreaterThan(0,$result_int);

    }


    public function tearDown()
    {
        $this->CI->migration->version(0);
    }






}