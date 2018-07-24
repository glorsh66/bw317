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



//////Убираем форен кеи
$this->db->query("ALTER TABLE `site_users` DROP FOREIGN KEY `FK_site_users_user_groups`;");
$this->db->query("ALTER TABLE `login_attempts` DROP FOREIGN KEY `FK_login_attempts_users`;");
$this->db->query("ALTER TABLE `users_sessions` DROP FOREIGN KEY `FK_users_sessions_users`;");
$this->db->query("ALTER TABLE `users_activation_code` DROP FOREIGN KEY `FK_users_activation_code`;");
$this->db->query("ALTER TABLE `users_tokens` DROP FOREIGN KEY `FK_login_users_tokens_user_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_greater_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_lesser_id` ;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_to_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_private_messages_user_from_id` ;");
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

$this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_lesser_id`;");
$this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_greater_id`;");
$this->db->query("ALTER TABLE `PM_board` DROP FOREIGN KEY `FK_PM_board_last_message_id`;");

$this->db->query("ALTER TABLE `PM_blacklist` DROP FOREIGN KEY `FK_PM_blacklist_owner`;");
$this->db->query("ALTER TABLE `PM_blacklist` DROP FOREIGN KEY `FK_PM_blacklist_banned`;");

$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_pm_from_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_pm_to_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_pm_lesser_id`;");
$this->db->query("ALTER TABLE `private_messages` DROP FOREIGN KEY `FK_pm_greater_id`;");


$this->db->query("ALTER TABLE `person` DROP FOREIGN KEY `FK_person_user_id`;");



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
          $this->dbforge->add_field("`password` CHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL"); //Добавляем поле для пароля
          $this->dbforge->add_field("`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
          $this->dbforge->add_field("`blocked_by_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");


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
                //Создаем  таблицу person
                $this->dbforge->drop_table('person',true); //Удаляем если есть такая таблица
                $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
                        // не работает втроеные ORM он требует обязательно наличия поля id
                        'type' => 'INT',
                        'constraint' => 9,
                        'unsigned' => TRUE,
                        'auto_increment' => FALSE // Так, как это будет ID пользователя и таблица будет жестко привязанна
                    ),
                    'height' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'Height of a person'
                    ),
                    'weight' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'weight of a person'
                    ),
                    'children' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'amount of children the person currently have'
                    ),
                    'sex' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sex of a person'
                    ),
                    'sexual_orientation' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'relationship' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'the relationship a person currently have'
                    ),
                    'education' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'education level of a person'
                    ),
                    'employment' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'employment status of a person'
                    ),
                    'smoke' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'alcohol' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'sport' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'health' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'virus_hiv' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
                    'virus_hepatitis_c' => array(
                        'type' => 'TINYINT',
                        'unsigned' => TRUE,
                        'comment' => 'sexual orientation of a person'
                    ),
 );

                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
                $this->dbforge->create_table('person');

//** ------------------------------------------------------------------------------------------------------------------------------------------------------------



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
//Создаем  таблицу users_activation_codes
          $this->dbforge->drop_table('users_activation_code',true); //Удаляем есои есть
           $fields = array(
       	    		    'user_that_will_be_activated_id' => array(
					    'type' => 'INT',
					     'constraint' => 9,
						'unsigned' => TRUE,
			    		),
                       'user_activation_code' => array(
                      'type' => 'VARCHAR',
                      'constraint' => '100',
                      'COLLATE' => 'utf8_bin',
                          ),
               );
$this->dbforge->add_field('`user_activation_code_change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
$this->dbforge->add_field($fields);
$this->dbforge->add_key('user_activation_code',TRUE);
$this->dbforge->add_key('user_that_will_be_activated_id');
$this->dbforge->create_table('users_activation_code');
//** ------------------------------------------------------------------------------------------------------------------------------------------------------------





//** ------------------------------------------------------------------------------------------------------------------------------------------------------------
          //Создаем  таблицу users_sessions

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
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field("`PM_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
    //      $this->dbforge->add_field("`has_been_read` TINYINT NOT NULL DEFAULT 0");
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->add_key(array('lesser_id', 'greater_id'));
            $this->dbforge->create_table('private_messages');
       //   $this->db->query("CREATE INDEX idx_pm_from_to_hasbeen_read ON private_messages (from_id, to_id,has_been_read)");
          $this->db->query("CREATE INDEX idx_pm_from_timestamp ON private_messages (from_id, PM_timestamp)");
          //TODO: Убрать потом этот индекс, после тестов.
          $this->db->query("CREATE INDEX idx_pm_lesser_greater_id ON private_messages (lesser_id,greater_id,id)");


 //** ----------------------------------------------------------------------------------------------------------------------------------------------


 //** -----------------------------------------------------------------------------------------------------------------------------------------------
 //Создаем  таблицу PM_blacklist
          $this->dbforge->drop_table('PM_blacklist',true); //Удаляем есои есть
           $fields = array(
                    'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
	          					   // не работает втроеные ORM он требует обязательно наличия поля id
	                'type' => 'INT',
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        			),

				    'owner' => array(
					  'type' => 'INT',
						'unsigned' => TRUE,
			    		),
			    	'banned' => array(
					  'type' => 'INT',
						'unsigned' => TRUE,
			    		), );
         // $this->dbforge->add_field('id');
          $this->dbforge->add_field("`PMBL_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
          $this->dbforge->create_table('PM_blacklist');
 //** ----------------------------------------------------------------------------------------------------------------------------------------------


 //** -----------------------------------------------------------------------------------------------------------------------------------------------
 //Создаем  таблицу PM_board
          $this->dbforge->drop_table('PM_board',true); //Удаляем есои есть
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

               );
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key(array('lesser_id', 'greater_id'),true);
          $this->dbforge->create_table('PM_board');
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



 //** -----------------------------------------------------------------------------------------------------------------------------------------------
           //Создаем  таблицу mail

           $this->dbforge->drop_table('mail',true); //Удаляем есои есть
            $fields = array(
                     'id' => array( //К сожалению можно только ID называть, хотя это не удобгно для дальнейшего Join, но codeigniter, к сожалению просто без id не
 	          					   // не работает втроеные ORM он требует обязательно наличия поля id
 	                'type' => 'INT',
 	                'unsigned' => TRUE,
 	                'auto_increment' => TRUE
 	        			),

 			    		 'mail_from' => array(
                           'type' =>'VARCHAR',
                           'null' => FALSE, // NOT Null
                           'constraint' => '255',
                          ),

              'mail_to' => array(
                          'type' =>'VARCHAR',
                          'null' => FALSE, // NOT Null
                          'constraint' => '255',
              ),

              'mail_subject' => array(
                          'type' =>'VARCHAR',
                          'null' => FALSE, // NOT Null
                          'constraint' => '255',
              ),

              'mail_text' => array(
                          'type' =>'VARCHAR',
                          'null' => FALSE,
                          'constraint' => '21000'

              ),
             );
          // $this->dbforge->add_field('id');
           $this->dbforge->add_field("`mail_timestamp_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
           $this->dbforge->add_field("`mail_timestamp_has_sent` timestamp ON UPDATE CURRENT_TIMESTAMP");
           $this->dbforge->add_field("`mail_has_been_sent` TINYINT NOT NULL DEFAULT 0");
           $this->dbforge->add_field($fields);
           $this->dbforge->add_key('id',TRUE);//Делаем ID основным ключем
           $this->dbforge->create_table('mail');




  //** ----------------------------------------------------------------------------------------------------------------------------------------------







//---------------------------------Foregin keys section-----------------------------------------------------------------------------------------
          //Добавляем Foreging keys т.к.
          //Делаем это после создания всех таблиц, т.к. для этого нужные уже созданные таблицы
$this->db->query("ALTER TABLE `site_users` ADD CONSTRAINT `FK_site_users_user_groups` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`);");
$this->db->query("ALTER TABLE `login_attempts` ADD CONSTRAINT `FK_login_attempts_users` FOREIGN KEY (`login_attempts_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `users_sessions` ADD CONSTRAINT `FK_users_sessions_users` FOREIGN KEY (`users_sessions_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `users_activation_code` ADD CONSTRAINT `FK_users_activation_code` FOREIGN KEY (`user_that_will_be_activated_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `users_tokens` ADD CONSTRAINT `FK_login_users_tokens_user_id` FOREIGN KEY (`login_users_tokens_user_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_greater_id` FOREIGN KEY (`greater_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_lesser_id` FOREIGN KEY (`lesser_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_to_id` FOREIGN KEY (`to_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_private_messages_user_from_id` FOREIGN KEY (`from_id`) REFERENCES `site_users` (`id`);");
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

$this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_lesser_id` FOREIGN KEY (`lesser_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_greater_id` FOREIGN KEY (`greater_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `PM_board` ADD CONSTRAINT `FK_PM_board_last_message_id` FOREIGN KEY (`last_message`) REFERENCES `private_messages` (`id`);");


$this->db->query("ALTER TABLE `PM_blacklist` ADD CONSTRAINT `FK_PM_blacklist_owner` FOREIGN KEY (`owner`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `PM_blacklist` ADD CONSTRAINT `FK_PM_blacklist_banned` FOREIGN KEY (`banned`) REFERENCES `site_users` (`id`);");

$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_pm_from_id` FOREIGN KEY (`from_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_pm_to_id` FOREIGN KEY (`to_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_pm_lesser_id` FOREIGN KEY (`lesser_id`) REFERENCES `site_users` (`id`);");
$this->db->query("ALTER TABLE `private_messages` ADD CONSTRAINT `FK_pm_greater_id` FOREIGN KEY (`greater_id`) REFERENCES `site_users` (`id`);");

$this->db->query("ALTER TABLE `person` ADD CONSTRAINT `FK_person_user_id` FOREIGN KEY (`id`) REFERENCES `site_users` (`id`);");


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
$this->Usermodel->insert_user_registration_with_any_user_group("admin","admin@admin.com","test55",1);
$this->Usermodel->insert_user_registration("user1","user1@mail.ru","test55");
$this->Usermodel->insert_user_registration("user2","user2@mail.ru","test55");
$this->Usermodel->insert_user_registration("user3","user3@mail.ru","test55");
$this->Usermodel->insert_user_registration("user4","user4@mail.ru","test55");
$this->Usermodel->insert_user_registration("user5","user5@mail.ru","test55");
$this->Usermodel->insert_user_registration("user6","user6@mail.ru","test55");
$this->Usermodel->insert_user_registration("user7","user7@mail.ru","test55");
$this->Usermodel->insert_user_registration("user8","user8@mail.ru","test55");
$this->Usermodel->insert_user_registration("user9","user9@mail.ru","test55");
$this->Usermodel->insert_user_registration("user10","user10@mail.ru","test55");
$this->Usermodel->insert_user_registration("user11","user11@mail.ru","test55");



//---------------------------------dummy data section end-----------------------------------------------------------------------------------------



//Делаем ссылку для возврата на главную страницу
$site_url = site_url();
echo "Все ваши базы захвачены";
echo "<br>";
echo "<p><a href=$site_url>Вернуться на главную страницу</a></p>";

		    }


}
?>
