<?php
class Login_attempts_model extends CI_Model {
 private static $this_table_name = 'login_attempts'; //название текущей таблицы, что бы менять в одном месте
 
  
  		
  		
public function insert_new_entry($name)
{	        	   
        $data = array(
        'login_attempts_user_name' => $name,       
        'login_attempts_ip_address'=> getenv('REMOTE_ADDR'), 
        'login_attempts_ip_address_if_proxy'=> getenv('HTTP_X_FORWARDED_FOR'),                    
        );        
        $this->db->insert(SELF::$this_table_name, $data);
        return $this->db->insert_id();             
}
  
  
public function count_results()
{  	 	
		$this->db->from(SELF::$this_table_name);
		return $this->db->count_all_results();
}
		      
public function del_old_entries()
{   	
		$this->db->from(SELF::$this_table_name);
		$this->db->where('login_attempts_time < now() - INTERVAL 1 SECOND');
		$this->db->where('login_attempts_user_name','test_guy');
		$this->db->delete();
		//$this->db->query("DELETE FROM login_attempts WHERE login_attempts_time  <	 now() - INTERVAL 15 SECOND;");  
		return $this->db->last_query();
		        }
}


?>