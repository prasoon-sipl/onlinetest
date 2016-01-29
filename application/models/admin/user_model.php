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
	function __construct()	{
		parent:: __construct();
	}  
	
	/*
	*Methodname:  getUser
        *Purpose: Get the user list to grid
 	*/
	
	public function getUser($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT user_id,full_name,email,is_active,created_date FROM '.TBL_TEST_USER.' WHERE is_deleted=0 AND user_id != 1';
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
	*Methodname:  checkUserEmail
        *Purpose: To check user email 
 	*/
	public function checkUserEmail($userEmail,$userId) {
                if(empty($userId))                                
	        $query = $this->db->query('SELECT `email` FROM `'.TBL_TEST_USER.'`  WHERE `email`="'.$this->db->escape_str(trim($userEmail)).'"');
		else
                $query = $this->db->query('SELECT `email` FROM `'.TBL_TEST_USER.'`  WHERE `email`="'.$this->db->escape_str(trim($userEmail)).'" AND user_id!= "'.$userId.'"');    
                if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
        }  
        
        
         /*
         *Methodname:  getUserDetails
        *Purpose: To get user details
 	*/
	public function getUserDetails($userId='') {
                if($userId!='') {                               
	          $query = $this->db->query('SELECT user_id,full_name,email,is_active FROM `'.TBL_TEST_USER.'`  WHERE `user_id` = "'.$this->db->escape_str(trim($userId)).'"');
                  if($query->num_rows() > 0) 
			return $query->row();                              
                } else{
                   $query = $this->db->query('SELECT user_id,full_name,email,is_active FROM `'.TBL_TEST_USER.'` WHERE is_active=1 AND is_deleted=0'); 
                if($query->num_rows() > 0) 
			return $query->result();
		
                }
                return false;
	}
        
        
        /*
	*Methodname:  addDetails
        *Purpose: General function to add details
        */
	
     public function addDetails($tblName, $data){
		$this->db->set('updated_date',date('Y-m-d H:i:s'));
		$this->db->insert($tblName, $data);
		return $this->db->insert_id();
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
/* Location: ./application/model/admin/user_model.php */