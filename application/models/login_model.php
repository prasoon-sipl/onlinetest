<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : login_model.php
* Purpose       : This file/class will handle login database function
*/
class Login_model extends CI_model {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    function __construct() {
        parent::__construct();
    }
    /*
    *Methodname:  checkUserNamePass
    *Purpose: Check user name and password for login form and set user session
    */
    public function checkUserNamePass($userEmail) {
        try {
            if (empty($userEmail)) throw new Exception('Email and password can not be blank');
            $this->db->select('user_id,email,full_name,password,is_active');
            $this->db->from(TBL_TEST_USER);
            $this->db->where('email', $userEmail);
            $this->db->where('is_deleted', 0);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result = $query->row();
                return $result;
            } else {
                $this->session->set_flashdata('message', 'Incorrect email address or password.');
                $this->session->set_flashdata('userEmail', $userEmail);
                return false;
            }
        }
        catch(Exception $e) {
            echo "Exception :" . $e->getMessage();
        }
    }
}
/* End of file login_model.php */
/* Location: ./application/controllers/login_model.php */