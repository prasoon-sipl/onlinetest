<?php
/*
 * Version       : 1.0
 * Filename      : usertest_model.php
 * Purpose       : This class is will handle user test view.
 */
class Usertest_model extends CI_model {
	/*
	*Methodname:  __construct
        *Purpose: Perform common action for class at load 
 	*/
	function __construct()	{
		parent:: __construct();
	}  
	
	/*
	*Methodname:  getUser
        *Purpose: Get the user test list 
 	*/
	
	public function getUsertest($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT user_test_id,test_title,fk_user_id,fk_question_category_id,no_of_question,test_duration,max_marks,questions_attempted,time_spent,test_status,marks_obtained,is_active,created_date FROM '.TBL_USER_TEST.' WHERE is_deleted=0';
		if (!empty($params)){
			if((($params ["num_rows"] * $params ["page"]) >= 0 && $params ["num_rows"] > 0)){
				if($params["search"] != 'false'){
					$ops = array(
						'eq'=>'=', //equal
						'ne'=>'<>',//not equal
						'lt'=>'<', //less than
						'le'=>'<=',//less than or equal
						'gt'=>'>', //greater than
						'ge'=>'>=',//greater than or equal
						'bw'=>'LIKE', //begins with
						'bn'=>'NOT LIKE', //doesn't begin with
						'in'=>'LIKE', //is in
						'ni'=>'NOT LIKE', //is not in
						'ew'=>'LIKE', //ends with
						'en'=>'NOT LIKE', //doesn't end with
						'cn'=>'LIKE', // contains
						'nc'=>'NOT LIKE'  //doesn't contain
					);
					// Gets the 'filters' object from JSON
					$filters = $this->input->post('filters');
					$filterResultsJSON = json_decode($filters,true);
					$groupOp = $filterResultsJSON['groupOp'];
					$rules = $filterResultsJSON['rules'];
					foreach($rules as $val){
						$val['field'];
						$val['op'];
						$val['data'];
						
						switch ($val['op']){
						case "eq":
						 	$sql .= ' AND '.$val['field'].' = "'.trim($val['data']).'"';
						  break;
						case "ne":
							$sql .= ' AND '.$val['field'].' != "'.trim($val['data']).'"';
						  break;
						case "lt":
							$sql .= ' AND '.$val['field'].' < "'.trim($val['data']).'"';
						  break;						  
						case "gt":
							$sql .= ' AND '.$val['field'].' > "'.trim($val['data']).'"';
						  break;
						case "le":
							$sql .= ' AND '.$val['field'].' <= "'.trim($val['data']).'"';
						  break;
						case "ge":
							$sql .= ' AND '.$val['field'].' >= "'.trim($val['data']).'"';
						  break;
						case "cn":
							$sql .= ' AND '.$val['field'].' LIKE "'.trim($val['data']).'%"';
						  break;
						case "ew":
							$sql .= ' AND '.$val['field'].' LIKE "%'.trim($val['data']).'"';
						  break;
						case "in":
							$sql .= ' AND '.$val['field'].' NOT LIKE "%'.trim($val['data']).'%"';
						  break;
						case "bw":
							$sql .= ' AND '.$val['field'].' LIKE "%'.trim($val['data']).'%"';
						  break;
						case "ni":
							$sql .= ' AND '.$val['field'].' NOT IN "('.trim($val['data']).')"';
						  break;
						case "en":
						  $sql .= ' AND '.$val['field'].' NOT LIKE "%'.trim($val['data']).'%"';
						  break;
						case "bn":
						  $sql .= ' AND '.$val['field'].' NOT LIKE "%'.trim($val['data']).'"';
						  break;			  						  
						case "nc":
						  $sql .= ' AND '.$val['field'].' LIKE "%'.trim($val['data']).'%"';
						  break;
						case "dt":
							if (strpos($val['data'],'/') !== false) {
								//$val['data'] = str_replace ("/","-",$val['data']);
							}
							$sql .= ' AND DATE_FORMAT('.$val['field'].',"%Y-%m-%d") = "'.date("Y-m-d",strtotime(trim($val['data']))).'"';
						  break;
					}	
					}
				}
				$sql .= ' ORDER BY '.$params['sort_by'].' '.$params ["sort_direction"];
				if ($count != true){
					if(isset($params['page']) && $params['page']!= 1){
						$sql .= ' limit '.$params["num_rows"]*($params["page"]-1).', '.$params["num_rows"]*$params["page"];
					}else{
						$sql .= ' limit 0, '.$params["num_rows"];
					}
				}else{
					//Do nothing...
				}
				$sql;
				$query = $this->db->query($sql);
			}
		}else{
			$query = $this->db->query($sql);	
		}
		return $query;
	}
        /*
	*Methodname:  updateDetail
        *Purpose: General function to update details
        */
	public function updateDetail($tblName, $colName, $id, $data){
		$this->db->set('updated_date',date('Y-m-d H:i:s'));
		if(is_array($colName)){
			for($i= 0; $i < count($colName); $i++)
				$this->db->where($colName[$i], $id[$i]);
		}else
			$this->db->where($colName, $id);
		
		return $this->db->update($tblName, $data);
	}	
	
}

/* End of file user_model.php */
/* Location: ./application/model/admin/usertest_model.php */