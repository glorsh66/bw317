<?php
class Db extends CI_Controller {

        public function delete()
        {
                $this->load->dbforge();
                $this->dbforge->drop_table('site_users');
        }

	  public function index()
	  {
	  	$this->createdb();
	  }


	        public function createdb()
        {
          //грузим либы
          $this->load->helper('url');

        	//Создаем таблицу пользователей

          $this->load->dbforge();

          //$this->db->query('CREATE DATABASE `test_ci` CHARACTER SET utf8 COLLATE utf8_general_ci;');



//Убираем форен кеи
$this->db->query("ALTER TABLE `site_users` DROP FOREIGN KEY `FK_site_users_user_groups`;");
$this->db->query("ALTER TABLE `login_attempts` DROP FOREIGN KEY `FK_login_attempts_users`;");
$this->db->query("ALTER TABLE `users_sessions` DROP FOREIGN KEY `FK_users_sessions_users`;");
$this->db->query("ALTER TABLE `users_tokens` DROP FOREIGN KEY `FK_login_users_tokens_user_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_id_reciver`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_id_sender` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_advets_user_id` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_categoty_id` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_cur1_id` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_cur2_id` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_cur3_id` ;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_cur4_id`;");
$this->db->query("ALTER TABLE `advets` DROP FOREIGN KEY `FK_ad_cur5_id` ;");
$this->db->query("ALTER TABLE `ad_rat` DROP FOREIGN KEY `FK_ad_id`;");
$this->db->query("ALTER TABLE `ad_rat` DROP FOREIGN KEY `FK_ad_rat_user_id_sender` ;");
$this->db->query("ALTER TABLE `user_rat` DROP FOREIGN KEY `FK_user_rat_user_id_reciver`;");
$this->db->query("ALTER TABLE `user_rat` DROP FOREIGN KEY `FK_puser_rat_user_id_sender`;");





          $this->dbforge->drop_table('site_users',true); //Удаляем есои есть
          $fields = array(
	          		'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),
                  'user_name' => array( //Уникальное нужно для создания индекса
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'unique' => TRUE,
                  ),
                  'user_email' => array( //Уникальное  нужно для создания индекса
                          'type' => 'VARCHAR',
                          'constraint' => '255',
                          'unique' => TRUE,
                  ),
                    'isactivated' => array(
                          'type' => 'tinyint',
                          'constraint' => '1',
                          'null' => FALSE, // NOT Null
                          'default' => '1',
                  ),

                          'ban_reason' => array(
                          'type' => 'tinyint',
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                          'null' => TRUE, // maybe null может быть null
                  ),
                          'ibanned' => array(
                          'type' => 'tinyint',
                          'constraint' => '1',
                          'null' => FALSE, // NOT Null
                          'default' => '0',
                  ),

                  'user_contacts_skype' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),
                   'user_adress' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '255',
                  ),

                    'user_adress_city' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),

                    'user_adress_region' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),

                       'user_adress_country' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),

                          'user_adress_state' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '100',
                  ),

                  'user_registration_ip' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '50',
                  ),

                         'user_registration_ip_if_proxy' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '50',
                  ),

                          'user_carma' => array(
                          'type' =>'int',
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
                         'type' =>'VARCHAR',
                       'constraint' => '50',
            ),

              'user_last_active_date' => array(
                        'type' => 'timestamp',

              ),

             'user_registration_date' => array(
                        'type' => 'timestamp',

              ),
            'group_id' => array(
		    'type' => 'INT',
		     'constraint' => 9,
			'unsigned' => TRUE,
    ),);
          $this->dbforge->add_field("`password` CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL"); //Добавляем поле для пароля
          $this->dbforge->add_field("`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");

          $this->dbforge->add_key('id',TRUE); //Делаем ID основным ключем
         // $this->dbforge->add_key('user_name'); //Создаем индекс для имени
          $this->dbforge->add_key('user_email'); //создаем индекс для емайла
          $this->dbforge->add_field($fields);
         $this->dbforge->create_table('site_users');
          //Создаем индексы
           $this->db->query("ALTER TABLE `site_users` DROP INDEX `user_name`, ADD UNIQUE INDEX `user_name` (`user_name`);");
           $this->db->query("ALTER TABLE `site_users` DROP INDEX `user_email`, ADD UNIQUE INDEX `user_email` (`user_email`);");
          //Меняем позиции столбов для лучшей наглядности
            $this->db->query("ALTER TABLE `site_users` CHANGE COLUMN password password CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER id;");
            $this->db->query("ALTER TABLE `site_users` CHANGE COLUMN `modified` `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER group_id;");




//** ------------------------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу user_groups
          $this->dbforge->drop_table('user_groups',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),
                          'groups_name' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                  ),
                  'groups_description' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '255',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('user_groups');

 //** ------------------------------------------------------------------------------------------------------------------------------------------------------------
   //** ------------------------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу Login_attempts


          $this->dbforge->drop_table('login_attempts',true); //Удаляем есои есть
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

        //  $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('login_attempts');




//** ------------------------------------------------------------------------------------------------------------------------------------------------------------

//** ------------------------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу Login_attempts


          $this->dbforge->drop_table('users_sessions',true); //Удаляем есои есть
           $fields = array(
               'users_sessions_selector' => array(
              'type' => 'VARCHAR',
              'constraint' => '60',
              'COLLATE' => 'utf8_bin',
                  ),
                  'users_sessions_validator' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                  ),
	            'users_sessions_user_id' => array(
			        'type' => 'INT',
			        'constraint' => 9,
				      'unsigned' => TRUE,
	   				 ),

           );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_field("`users_sessions_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
          $this->dbforge->add_key('users_sessions_selector',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('users_sessions');


//** -----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу users_tokens

          $this->dbforge->drop_table('users_tokens',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),
                          'users_tokens_token' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                  ),
                   'last_activity' => array(
                          'type' => 'timestamp',
                  ),

                  'login_users_tokens_user_id' => array(
                 'type' => 'INT',
                  'constraint' => 9,
               'unsigned' => TRUE,
                 ),


                  'time' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('users_tokens');

 //** ----------------------------------------------------------------------------------------------------------------------------------------------



//** -----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу private_messages

          $this->dbforge->drop_table('private_messages',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

				          'users_tokens_token' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                  ),


				        'sender_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),
			    		 'reciver_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),

			    		 'pm_message' => array(
                          'type' =>'TEXT',
                  		  ),


                          'ip_address' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                  ),


                  'time_of_try' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('private_messages');




 //** ----------------------------------------------------------------------------------------------------------------------------------------------



//** ----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу crypto_currencies
          $this->dbforge->drop_table('crypto_currencies',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

				        'ammoun_of_digits_before_dot' => array(
					    'type' => 'BIGINT',
					    'unsigned' => TRUE,
			    		),
			    		 'maximum_amount_after_dot' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),

			    		'is_keeped_in_text_format' => array(
					    'type' => 'tinyINT',
					     'constraint' => 1,
						'unsigned' => false,
			    		),

			    		 'description' => array(
                          'type' =>'TEXT',
                  		  ),
                          'short_description' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),
                          'cur_name' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),
                          'cur_short_name' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),
                          'cur_img' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),
                          'cur_big_img' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),
                          'cur_date_of_creation' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                          ),


                          'time_of_try' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('crypto_currencies');

 //** ----------------------------------------------------------------------------------------------------------------------------------------------





//** ----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу crypto_currencies
          $this->dbforge->drop_table('advets',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

                'advets_user_id' => array(
               'type' => 'INT',
                'constraint' => 9,
             'unsigned' => TRUE,
               ),

  						'ad_title' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                         ),
                        'ad_short_description' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_short_description' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_text' => array(
                        'type' =>'TEXT',
                  		),
                  		'ad_img_1' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_img_2' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_img_3' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_img_4' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_img_5' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                  		'ad_openbazaar_link' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_openbazaar_link' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_youtube_link' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_skype' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_adress' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_state' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_country' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),
                        'ad_city' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'COLLATE' => 'utf8_bin',
                        ),

                         'ad_cur1_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),

    					 'ad_cur1_price' => array(
		    			 'type' => 'INT',
						 'unsigned' => TRUE,
    					 ),

    					 'ad_cur1_pricet_text' => array(
		    			 'type' => 'VARCHAR',
                         'constraint' => '50',
    					 ),
    					 'ad_cur2_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur2_price' => array(
		    			 'type' => 'INT',
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur2_price_text' => array(
		    			 'type' => 'VARCHAR',
                         'constraint' => '50',
    					 ),
    					 'ad_cur3_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur3_price' => array(
		    			 'type' => 'INT',
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur3_price_text' => array(
		    			 'type' => 'VARCHAR',
                         'constraint' => '50',
    					 ),
    					 'ad_cur4_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur4_price' => array(
		    			 'type' => 'INT',
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur4_price_text' => array(
		    			 'type' => 'VARCHAR',
                         'constraint' => '50',
    					 ),
    					  'ad_cur5_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur5_price' => array(
		    			 'type' => 'INT',
						 'unsigned' => TRUE,
    					 ),
    					 'ad_cur5_price_text' => array(
		    			 'type' => 'VARCHAR',
                         'constraint' => '50',
    					 ),
    					 'ad_rating' => array(
					     'type' => 'INT',
			    		 ),
    					  'ad_isactivated' => array(
                          'type' => 'tinyint',
                          'constraint' => '1',
                          'null' => FALSE, // NOT Null
                          'default' => '1',
                  		  ),
                  		  'ad_isblocked' => array(
                          'type' => 'tinyint',
                          'constraint' => '1',
                          'null' => FALSE, // NOT Null
                          'default' => '1',
                  		  ),
                  		  'ad_cause_of_block' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                       	  ),


                       	 'ad_categoty_id' => array(
		    			 'type' => 'INT',
		     			 'constraint' => 9,
						 'unsigned' => TRUE,
    					 ),

                          'ad_time_of_change' => array(
                          'type' =>'timestamp',
                 		   ),

                          'ad_time_of_add' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('advets');
          $this->db->query("ALTER TABLE `advets` CHANGE `ad_time_of_add` `ad_time_of_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;");
          $this->db->query("ALTER TABLE `advets` CHANGE `ad_time_of_change` `ad_time_of_change` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP;");


 //** ----------------------------------------------------------------------------------------------------------------------------------------------



//** --------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу ad_categories
          $this->dbforge->drop_table('ad_categories',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),
                          'cat_name' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                  ),
                  'cat_description' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '255',
                  ),

                 'ad_cat_parent_id' => array(
		    	 'type' => 'INT',
		     	 'constraint' => 9,
				 'unsigned' => TRUE,
    			 ),
    			 'ad_cat_sub_parent_id' => array(
		    	 'type' => 'INT',
		     	 'constraint' => 9,
				 'unsigned' => TRUE,
    			 ),
    			 'ad_cat_level' => array(
		    	 'type' => 'INT',
		     	 'constraint' => 9,
				 'unsigned' => TRUE,
    			 ),
    			 'ad_cat_group' => array(
		    	 'type' => 'INT',
		     	 'constraint' => 9,
				 'unsigned' => TRUE,
    			 ),




                   );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('ad_categories');

//** ---------------------------------------------------------------------------------------------------------------------------------------------




//** -----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу ad_rat

          $this->dbforge->drop_table('ad_rat',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

              'ad_rat_ad_id' => array(
					    'type' => 'INT',
					    'constraint' => 9,
						  'unsigned' => TRUE,
			    		),

				        'ad_rat_sender_user_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),


			    		 'ad_rat_message' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '255',
                         ),

                          'ad_rat_rate' => array(
		    			  'type' => 'TINYINT',
		     			  'unsigned' => TRUE,
    					  ),

                          'ip_address' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                  ),


                  'time_of_try' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('ad_rat');




 //** ----------------------------------------------------------------------------------------------------------------------------------------------



//** -----------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу user_rat

          $this->dbforge->drop_table('user_rat',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'constraint' => 9,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

				        'us_rat_sender_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),

			    		 'us_rat_reciver_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),

			    		 'us_rat_message' => array(
                          'type' =>'VARCHAR',
                          'constraint' => '255',
                         ),

                          'us_rat_rate' => array(
		    			  'type' => 'TINYINT',
		     			  'unsigned' => TRUE,
    					  ),

                          'ip_address' => array(
                          'type' => 'VARCHAR',
                          'constraint' => '50',
                          'COLLATE' => 'utf8_bin',
                  ),


                  'time_of_try' => array(
                          'type' =>'timestamp',
                  ), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('user_rat');




 //** ----------------------------------------------------------------------------------------------------------------------------------------------








//---------------------------------Foregin keys section-----------------------------------------------------------------------------------------
          //Добавляем Foreging keys т.к.
          //Делаем это после создания всех таблиц, т.к. для этого нужные уже созданные таблицы
$this->db->query("ALTER TABLE `site_users` ADD CONSTRAINT `FK_site_users_user_groups` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`);");
$this->db->query("ALTER TABLE `login_attempts` ADD CONSTRAINT `FK_login_attempts_users` FOREIGN KEY (`login_attempts_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `users_sessions` ADD CONSTRAINT `FK_users_sessions_users` FOREIGN KEY (`users_sessions_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `users_tokens` ADD CONSTRAINT `FK_login_users_tokens_user_id` FOREIGN KEY (`login_users_tokens_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_id_reciver` FOREIGN KEY (`reciver_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_id_sender` FOREIGN KEY (`sender_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_advets_user_id` FOREIGN KEY (`advets_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_categoty_id` FOREIGN KEY (`ad_categoty_id`) REFERENCES `ad_categories` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_cur1_id` FOREIGN KEY (`ad_cur1_id`) REFERENCES `crypto_currencies` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_cur2_id` FOREIGN KEY (`ad_cur2_id`) REFERENCES `crypto_currencies` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_cur3_id` FOREIGN KEY (`ad_cur3_id`) REFERENCES `crypto_currencies` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_cur4_id` FOREIGN KEY (`ad_cur4_id`) REFERENCES `crypto_currencies` (`id`);");
$this->db->query("ALTER TABLE `advets` ADD CONSTRAINT `FK_ad_cur5_id` FOREIGN KEY (`ad_cur5_id`) REFERENCES `crypto_currencies` (`id`);");
$this->db->query("ALTER TABLE `ad_rat` ADD CONSTRAINT `FK_ad_id` FOREIGN KEY (`ad_rat_ad_id`) REFERENCES `advets` (`id`);");
$this->db->query("ALTER TABLE `ad_rat` ADD CONSTRAINT `FK_ad_rat_user_id_sender` FOREIGN KEY (`ad_rat_sender_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `user_rat` ADD CONSTRAINT `FK_user_rat_user_id_reciver` FOREIGN KEY (`us_rat_reciver_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `user_rat` ADD CONSTRAINT `FK_puser_rat_user_id_sender` FOREIGN KEY (`us_rat_sender_id`) REFERENCES `site_users` (`id`);");








//---------------------------------Foregin keys section end-----------------------------------------------------------------------------------------


//---------------------------------dummy data section-----------------------------------------------------------------------------------------
		//Добавляем Users groups
$this->load->model('Usermodel');
$this->Usermodel->insert_user_group("admin","The most important guy here period.");
$this->Usermodel->insert_user_group("moderators","Can delete or edit posts");
$this->Usermodel->insert_user_group("super_moderators","Can delete or edit posts");
$this->Usermodel->insert_user_group("users","Just a regual Joe. Nothing special");
$this->Usermodel->insert_user_group("shop-keepers","Guys who use site quite intensively.");

		//Добавляем Users


$this->Usermodel->insert_user_registration_form_with_id("admin","admin@admin.com","test55",1);
$this->Usermodel->insert_user_registration_form("user1","user1@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user2","user2@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user3","user3@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user4","user4@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user5","user5@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user6","user6@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user7","user7@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user8","user8@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user9","user9@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user10","user10@mail.ru","test55");
$this->Usermodel->insert_user_registration_form("user11","user11@mail.ru","test55");



//---------------------------------dummy data section end-----------------------------------------------------------------------------------------



//Делаем ссылку для возврата на главную страницу
$site_url = site_url();
echo "Все ваши базы захвачены";
echo "<br>";
echo "<p><a href=$site_url>Вернуться на главную страницу</a></p>";

		    }


}
?>
