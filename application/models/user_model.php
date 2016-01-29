<?php
/*
* Version       : 1.0
* Filename      : user_model.php
* Purpose       : This class is will handle user function.
*/
class User_model extends CI_model {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    function __construct() {
        parent::__construct();
    }
    /*
    *Methodname:  checkUserEmail
    *Purpose: To check user email
    */
    public function checkUserEmail($userEmail, $userId) {
        if (empty($userId)) $query = $this->db->query('SELECT `email`,user_id,full_name FROM `' . TBL_TEST_USER . '`  WHERE `email`="' . $this->db->escape_str(trim($userEmail)) . '"');
        elseif (empty($userEmail)) $query = $this->db->query('SELECT `email`,user_id,full_name FROM `' . TBL_TEST_USER . '`  WHERE `user_id`="' . $this->db->escape_str(trim($userId)) . '"');
        else $query = $this->db->query('SELECT `email`,user_id,full_name FROM `' . TBL_TEST_USER . '`  WHERE `email`="' . $this->db->escape_str(trim($userEmail)) . '" AND user_id = "' . $userId . '"');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    /*
    *Methodname:  getUserDetails
    *Purpose: To get user details
    */
    public function getUserDetails($userId = '') {
        if ($userId != '') {
            $query = $this->db->query('SELECT user_id,full_name,email,is_active FROM `' . TBL_TEST_USER . '`  WHERE `user_id` = "' . $this->db->escape_str(trim($userId)) . '"');
            if ($query->num_rows() > 0) return $query->row();
        } else {
            $query = $this->db->query('SELECT user_id,full_name,email,is_active FROM `' . TBL_TEST_USER . '` WHERE is_active=1 AND is_deleted=0');
            if ($query->num_rows() > 0) return $query->result();
        }
        return false;
    }
    /*
    *Methodname:  addDetails
    *Purpose: General function to add details
    */
    public function addDetails($tblName, $data) {
        $this->db->set('updated_date', date('Y-m-d H:i:s'));
        $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    /*
    *Methodname:  updateDetail
    *Purpose: General function to update details
    */
    public function updateDetail($tblName, $colName, $id, $data) {
        $this->db->set('updated_date', date('Y-m-d H:i:s'));
        if (is_array($colName)) {
            for ($i = 0;$i < count($colName);$i++) $this->db->where($colName[$i], $id[$i]);
        } else $this->db->where($colName, $id);
        return $this->db->update($tblName, $data);
    }
    /*
    *Methodname:  sendEmail
    *Purpose: To send Email
    */
    public function sendEmail($to, $from, $subject, $mailContent, $attchmentFileLocation = '') {
        if (count($this->config->item('cust_email'))) $this->load->library('email', $this->config->item('cust_email'));
        else $this->load->library('email');
        //$this->load->library('email');
        $this->email->mailtype = 'html';
        $this->email->set_newline("\zr\n");
        $this->email->set_newline("\r\n");
        $this->email->from($from, 'App Catalyser');
        $this->email->to($to);
        $this->email->subject($subject);
        $data['emailContent'] = $mailContent;
        $emailBody = $this->load->view('template/email_template_view', $data, true);
        $this->email->message($emailBody);
        if (!empty($attchmentFileLocation)) $this->email->attach($attchmentFileLocation);
        if ($this->email->send()) return true;
        else return false;
    }
    /*
    *Methodname:  getClientIP
    *Purpose: functiuon return IP address
    */
    public function getClientIP() {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) return $_SERVER["HTTP_X_FORWARDED_FOR"];
            if (isset($_SERVER["HTTP_CLIENT_IP"])) return $_SERVER["HTTP_CLIENT_IP"];
            return $_SERVER["REMOTE_ADDR"];
        }
        if (getenv('HTTP_X_FORWARDED_FOR')) return getenv('HTTP_X_FORWARDED_FOR');
        if (getenv('HTTP_CLIENT_IP')) return getenv('HTTP_CLIENT_IP');
        return getenv('REMOTE_ADDR');
    }
    /*
    *Methodname:  getBrowser
    *Purpose: addUser's system activity log
    */
    public function getBrowser() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) return 'Internet explorer';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
        return 'Internet explorer';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) return 'Mozilla Firefox';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) return 'Google Chrome';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE) return "Opera Mini";
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) return "Opera";
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) return "Safari";
        else return '';
    }
    /*
    *Methodname:  saveActivityLog
    *Purpose: addUser's system activity log
    */
    public function saveActivityLog($userId, $activityType, $activityDescription, $tableName = '', $tableId = '') {
        $tableData['created_date'] = date('Y-m-d H:i:s');
        $tableData['updated_date'] = date('Y-m-d H:i:s');
        $tableData['activity_type'] = $activityType;
        $tableData['activity_description'] = $activityDescription;
        $tableData['table_name'] = $tableName;
        $tableData['table_id'] = $tableId;
        $tableData['ipaddress'] = $this->getClientIP();
        $tableData['browser'] = $this->getBrowser();
        $this->db->insert(TBL_ACTIVITY_LOG, $tableData);
    }
}
/* End of file user_model.php */
/* Location: ./application/model/user_model.php */