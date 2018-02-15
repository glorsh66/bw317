<?php
class Dbase extends CI_Controller {

        public function delete()
        {
                $this->load->dbforge();
                $this->dbforge->drop_table('site_users');
        }

	  
	  
	  public function create_groupsdb()
	  {
	  	//Создаем другую таблицу
	  	 $this->load->dbforge();

          
          
	  }
	  
	  
	        public function createdb()
        {
          $this->load->dbforge();
      //    $this->dbforge->drop_table('site_users',true);

          $fields = array(
                  'user_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'unique' => TRUE,
                  ),
                  'user_email' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '255',
                  ),
                  'user_contacts_skype' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_phone' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_phone2' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_phone3' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_icq' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_jabber' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_facebook' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_instagram' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_twitter' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                  'user_contacts_addit_info' => array(
                          'type' =>'TEXT',
                  ),
                  'user_contacts_www' => array(
                          'type' =>'TEXT',
                  ),
                  'user_bio' => array(
                          'type' =>'TEXT',
                  ),
                  'user_img' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '255',
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
                        'type' => 'VARCHAR',
                        'constraint' => '255',
              ),
              'user_ip_last_active' => array(
                      'type' => 'VARCHAR',
                      'constraint' => '255',
            ), );
          $this->dbforge->add_field("`password` CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL");
          $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->create_table('site_users');  
                
          $this->db->query("ALTER TABLE `site_users` ADD CONSTRAINT `FK_site_users_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);");
             
         
                    



		    }


}
?>
