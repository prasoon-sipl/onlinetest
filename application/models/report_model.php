<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : report_model.php
* Purpose       : This file/class will handle login database function
*/
class Report_model extends CI_model {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    function __construct() {
        parent::__construct();
    }
    /*
    *Methodname:  getReports
    *Purpose: get users reports
    */
    function getTestReports($userId, $testId = '') {
        $this->db->select('usertest.user_test_id,usertest.fk_question_category_id,usertest.no_of_question,usertest.test_duration,usertest.max_marks,usertest.questions_attempted,usertest.marks_obtained,usertest.percent_get,date_format(usertest.created_date,"%M-%d-%Y") as created_date,date_format(usertest.updated_date,"%M-%d-%Y %H:%i") as updated_date,category.category_title,category.category_title', false);
        $this->db->from(TBL_USER_TEST . ' as usertest');
        $this->db->join(TBL_QUESTION_CATEGORY . ' as category', 'usertest.fk_question_category_id=category.question_category_id', 'INNER');
        $this->db->where('usertest.fk_user_id', $userId);
        $this->db->where('usertest.is_active', 1);
        $this->db->where('usertest.is_deleted', 0);
        $this->db->where('usertest.test_status', 1);
        if (!empty($testId)) $this->db->where('usertest.user_test_id', $testId);
        $result = $this->db->get();
        if ($result->num_rows()) {
            if (!empty($testId)) return $result->row();
            return $result->result();
        }
        return false;
    }
}
/* End of file login_model.php */
/* Location: ./application/models/report_model.php */