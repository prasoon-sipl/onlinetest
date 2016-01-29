<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Version       : 1.0
 * Filename      : login_model.php
 * Purpose       : This file/class will handle admin login database function
 */

class Login_model extends CI_model {
	
	/*
	*Methodname:  __construct
        *Purpose: Perform common action for class at load 
	*/
	function __construct()	{
		parent:: __construct();
	}   

	/*
	*Methodname:  checkUserNamePass
        *Purpose: Check user name and password for login form and set user session
	*/
	public function checkUserNamePass($email) {
		try {
			if(empty($email))
				throw new Exception('Email can not be blank');
			
			$this->db->select('user_id, full_name, email, password,is_active');
			$this->db->from(TBL_TEST_USER);		
			$this->db->where('email', $email);
                        $this->db->where('user_id', 1);
			$query = $this->db->get();
			
			if($query->num_rows()) {
				return $query->row();		
			} else {
				$this->session->set_flashdata('email', $email);
				$this->session->set_flashdata('errorMsg', 'Incorrect email address or password.');	
				return false;	
			}
		}
		catch(Exception $e) {
			echo "Exception :".$e->getMessage();
		}	
	}
       /*
	*Methodname:  getUnreadMessageCount
        *Purpose: get user unread message count
       */
	public function getUnreadMessageCount($userId){
    	$query =  $this->db->query('SELECT count(`message_id`) AS `count` FROM `'.TBL_MESSAGE.'` WHERE `is_read`=0 and `fk_to_user_id`= '.$userId.' AND (!find_in_set('.$userId.',`deleted_for_user`) OR `deleted_for_user` IS NULL)' );
        if($query->num_rows())			
        	return $query->row();
	   	return  false;  
    }
}

/* End of file login_model.php */
/* Location: ./application/controllers/admin/login_model.php */