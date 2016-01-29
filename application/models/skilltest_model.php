<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : skilltest_model.php
* Purpose       : This file/class will handle login database function
*/
class SkillTest_model extends CI_model {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    function __construct() {
        parent::__construct();
    }
    /*
    *Methodname:  getSubCategoryDetails
    *Purpose: Get sub category details
    */
    public function getSubCategoryDetails($categoryName) {
        $result = false;
        $query = $this->db->query("SELECT `question_category_id`,`category_title`,`parent_category_id`,`test_duration`,`total_marks`,`no_of_questions`,`difficulty_levels`  FROM `" . TBL_QUESTION_CATEGORY . "` WHERE category_title= '" . $categoryName . "' AND parent_category_id IS NOT NULL AND is_active=1 AND is_deleted = 0");
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  checkUserTestStatus
    *Purpose: check user test status
    */
    public function checkUserTestStatus($testId, $userId) {
        $result = false;
        $query = $this->db->query('SELECT user_test_id FROM ' . TBL_USER_TEST . ' WHERE user_test_id =' . $testId . ' AND fk_user_id=' . $userId . ' AND test_status=0');
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getLevelQuestions
    *Purpose: Get given level and category questions
    */
    public function getLevelQuestions($categoryId, $lavel, $limit) {
        $result = false;
        $query = 'SELECT GROUP_CONCAT( tbl2.questions_ids ) AS questions_ids 
FROM ( 
SELECT `questions_id` AS questions_ids 
FROM ' . TBL_QUESTION . ' 
WHERE FIND_IN_SET( ' . $categoryId . ', `sub_categories` ) >0 
AND `fk_difficulty_levels_id` = ' . $lavel . ' 
AND is_active =1 
AND is_deleted =0 
ORDER BY rand( ) ASC 
LIMIT 0 , ' . $limit . ' 
) AS tbl2';
        $query = $this->db->query($query);
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getQuestion
    *Purpose: get question id json from tbl_user_test
    */
    public function getQuestionId($testId) {
        $result = false;
        $query = $this->db->query('SELECT questions_json FROM ' . TBL_USER_TEST . ' WHERE user_test_id =' . $testId);
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getQuestionDetails
    *Purpose: get question details using quesion id
    */
    public function getQuestionDetails($questionId) {
        $result = false;
        $query = $this->db->query('SELECT `questions_id` , `question_title` , `answer_type` , `no_of_options` , `question_description` , `answer_id` , `answer_description` , `is_correct`
FROM ' . TBL_QUESTION . ' AS qus 
INNER JOIN ' . TBL_ANSWER . ' AS ans ON qus.`questions_id` = ans.fk_questions_id 
AND ans.is_active =1 
AND ans.is_deleted =0 
AND qus.questions_id =' . $questionId . ' 
WHERE questions_id =' . $questionId . ' 
');
        if ($query->num_rows > 0) $result = $query->result();
        return $result;
    }
    /*
    *Methodname:  updateUserTest
    *Purpose: Update user test table for each attempted question
    */
    public function updateUserTest($userTestId, $questionJson, $attempted, $marksObtained, $timeSpent) {
        $query = $this->db->query('UPDATE ' . TBL_USER_TEST . ' SET questions_json=\'' . $questionJson . '\',questions_attempted= questions_attempted +' . $attempted . ',marks_obtained=marks_obtained+' . $marksObtained . ',time_spent=' . $timeSpent . ' WHERE user_test_id =' . $userTestId);
        return $query;
    }
    /*
    *Methodname:  getCurrentPercent
    *Purpose: Update user test table for each attempted question
    */
    public function getCurrentPercent($userTestId, $currentLevelFlag, $marksFrom) {
        $result = false;
        $query = $this->db->query('SELECT (SUM(marks_obtained)/' . $marksFrom . ')*100 AS percent FROM (SELECT marks_obtained FROM `' . TBL_USER_TEST_ANSWER . '` WHERE `fk_user_test_id` = ' . $userTestId . ' ORDER BY `user_test_answer_id` DESC LIMIT ' . $currentLevelFlag . ') AS tbl');
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getCurrentPercent
    *Purpose: Update user test table for each attempted question
    */
    public function checkTestTime($timeSpent, $userTestId) {
        $result = false;
        $query = $this->db->query('SELECT `user_test_id` FROM ' . TBL_USER_TEST . ' WHERE `test_duration` >= ' . $timeSpent . '/60 AND `user_test_id` = ' . $userTestId);
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  checkTestCompleted
    *Purpose: Update user test table for each attempted question
    */
    public function checkTestCompleted($userTestId) {
        $result = false;
        $query = $this->db->query('SELECT `user_test_id` FROM ' . TBL_USER_TEST . ' WHERE user_test_id = ' . $userTestId . ' AND `no_of_question` <= `questions_attempted`');
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  calculateTestMarks
    *Purpose: Calculate user test marks
    */
    public function calculateTestMarks($userTestId) {
        $result = false;
        $query = $this->db->query('SELECT SUM(`marks_obtained`) AS total_marks FROM ' . TBL_USER_TEST_ANSWER . ' WHERE `fk_user_test_id` = ' . $userTestId);
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getTestDetails
    *Purpose: Update user test table for each attempted question
    */
    public function getTestDetails($userTestId, $userId) {
        $result = false;
        $query = $this->db->query("SELECT `user_test_id`, test_status FROM " . TBL_USER_TEST . " WHERE user_test_id = " . $userTestId . " AND test_status = 0 AND is_active = 1 AND is_deleted = 0 AND fk_user_id = " . $userId);
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  getTestCategory
    *Purpose: to check user test payment status
    */
    public function checkUserTestPaymentStatus($categoryId, $userId) {
        $result = false;
        $query = $this->db->query("SELECT user_test.`user_test_id`,user_test.test_status,payment.state,payment.paymentgetway_pay,payment.total_pay,payment.promocode_title,payment.promocode_discount,payment.checkout_id,payment.created_date,cat.question_category_id FROM " . TBL_USER_TEST . " AS user_test

LEFT JOIN " . TBL_QUESTION_CATEGORY . " as cat ON user_test.fk_question_category_id = cat.question_category_id AND cat.is_active=1 AND cat.question_category_id = " . $categoryId . "

LEFT JOIN " . TBL_TEST_PAYMENT . " as payment ON user_test.user_test_id = payment.fk_user_test_id  
  
WHERE user_test.fk_question_category_id = " . $categoryId . " AND user_test.fk_user_id = " . $userId . " AND user_test.test_status = 2 AND payment.payment_status = 1 AND payment.state  IN('authorized','captured','promo') AND user_test.is_active = 1 AND user_test.is_deleted = 0 AND payment.is_active = 1 AND payment.is_deleted = 0 

");
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
    /*
    *Methodname:  updatePromocode
    *Purpose: update promocode details
    */
    public function updatePromocode($promocodeTitle) {
        $result = false;
        $query = $this->db->query('UPDATE ' . TBL_PROMOCODE . ' SET  `no_of_use` = `no_of_use` - 1 WHERE `promocode_title` = "' . $promocodeTitle . '"');
        return $query;
    }
    /*
    *Methodname:  sendEmail
    *Purpose: To send Email
    */
    public function sendEmail($to, $from, $subject, $mailContent, $attchmentFileLocation = '') {
        if (count($this->config->item('cust_email'))) $this->load->library('email', $this->config->item('cust_email'));
        else $this->load->library('email');
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
        $tableData['fk_user_id'] = $userId;
        $tableData['activity_description'] = $activityDescription;
        $tableData['table_name'] = $tableName;
        $tableData['table_id'] = $tableId;
        $tableData['ipaddress'] = $this->getClientIP();
        $tableData['browser'] = $this->getBrowser();
        $this->db->insert(TBL_ACTIVITY_LOG, $tableData);
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
}
/* End of file skilltest_model.php */
/* Location: ./application/models/skilltest_model.php */