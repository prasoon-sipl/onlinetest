<?php
/*
 * Version       : 1.0
 * Filename      : dashboard_model.php
 * Purpose       : This class is will handle database function of dashboard view.
 */
class Dashboard_model extends CI_model {
	/*
	*Methodname:  __construct
        *Purpose: Perform common action for class at load 
 	*/
	function __construct()	{
		parent:: __construct();
	}  
	
		
	/*
	*Methodname:  getTestCategory
        *Purpose: Get the available test category
 	*/
	public function getTestCategory(){
		$result = false;
		$query = $this->db->query("SELECT c0.question_category_id, c0.category_title, group_concat( c1.question_category_id
ORDER BY c1.created_date DESC ) AS child_cat_id, group_concat( c1.category_title
ORDER BY c1.created_date DESC ) AS child_cat_name,group_concat( c1.test_duration
ORDER BY c1.created_date DESC ) AS child_cat_test_duration,group_concat( c1.no_of_questions
ORDER BY c1.created_date DESC ) AS child_cat_no_of_questions
FROM ".TBL_QUESTION_CATEGORY." AS c0
LEFT JOIN ".TBL_QUESTION_CATEGORY." AS c1 ON c0.question_category_id = c1.parent_category_id
AND c1.is_active =1
AND c1.is_deleted =0
WHERE c0.is_active =1
AND c0.is_deleted =0
AND c1.parent_category_id IS NOT NULL
AND c1.question_category_id != ".DEFAULT_CATEGORY."
GROUP BY c0.question_category_id
ORDER BY c1.created_date DESC
");
		
		if($query->num_rows > 0)
			$result = $query->result();
		return $result;
	}
}

/* End of file dashboard_model.php */
/* Location: ./application/model/admin/dashboard_model.php */