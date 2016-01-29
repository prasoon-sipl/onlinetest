<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : dashboard_model.php
* Purpose       : This file/class will handle dashboard database function
*/
class Dashboard_model extends CI_model {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    function __construct() {
        parent::__construct();
    }
    /*
    *Methodname:  getTestCategory
    *Purpose: Get the available test category
    */
    public function getTestCategory($userId) {
        $query = 'SELECT group_concat( ifnull(giventest.perc,0) ORDER BY c1.created_date DESC ) AS perc ,c0.question_category_id, c0.category_title, group_concat( c1.question_category_id
ORDER BY c1.created_date DESC ) AS child_cat_id, group_concat( c1.category_title 
ORDER BY c1.created_date DESC ) AS child_cat_name,group_concat( c1.test_duration 
ORDER BY c1.created_date DESC ) AS child_cat_test_duration,group_concat( c1.no_of_questions 
ORDER BY c1.created_date DESC ) AS child_cat_no_of_questions 
FROM ' . TBL_QUESTION_CATEGORY . ' AS c0 
LEFT JOIN ' . TBL_QUESTION_CATEGORY . ' AS c1 ON c0.question_category_id = c1.parent_category_id 
AND c1.is_active =1 AND c1.is_deleted =0 AND c1.no_of_questions !=0 
LEFT JOIN (SELECT fk_question_category_id, max(round(marks_obtained/max_marks *100)) as perc 
FROM ' . TBL_USER_TEST . ' AS usertest WHERE `fk_user_id`=' . $userId . ' AND usertest.is_active=1 AND usertest.is_deleted=0 AND usertest.test_status=1  GROUP BY fk_question_category_id   
) as giventest ON c1.question_category_id=giventest.fk_question_category_id 
WHERE c0.is_active =1 
AND c0.is_deleted =0 
AND c1.parent_category_id IS NOT NULL AND c1.question_category_id != ' . DEFAULT_CATEGORY . ' 
GROUP BY c0.question_category_id 
ORDER BY c1.created_date DESC';
        $result = false;
      
        $query = $this->db->query($query);
        if ($query->num_rows > 0) $result = $query->result();
        return $result;
    }
    /*
    *Methodname:  checkCategoryStatus
    *Purpose: to check category test status
    */
    public function checkCategoryStatus($categoryTitle) {
        $result = false;
        $query = $this->db->query("SELECT  question_category_id FROM " . TBL_QUESTION_CATEGORY . " WHERE `category_title` = '" . $categoryTitle . "' and is_active = 1 AND is_deleted=0");
        if ($query->num_rows > 0) $result = $query->row();
        return $result;
    }
}
/* End of file dashboard_model.php */
/* Location: ./application/models/dashboard_model.php */