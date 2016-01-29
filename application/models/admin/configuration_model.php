<?php
/*
 * Version       : 1.0
 * Filename      : configuration_model.php
 * Purpose       : This class is will handle database function of configuration view.
 */
class Configuration_model extends CI_model {

	/*
	*Methodname:  __construct
        *Purpose: Perform common action for class at load 
 	*/
	function __construct()	{
		parent:: __construct();
	}
	
	/*
	*Methodname:  getQuestionsParentCategory
        *Purpose: Get the parent category list 
 	*/
	public function getQuestionsParentCategory(){
		$this->db->select('question_category_id,category_title');
		$this->db->from(TBL_QUESTION_CATEGORY);
		//$this->db->where('question_category_id=parent_category_id',NULL,false);
		$this->db->where('parent_category_id IS NULL',NULL,false);
		$this->db->where('is_active',1);
		$this->db->where('is_deleted',0);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows())
			return $result->result();
		return false;
	}
	
	/*
	*Methodname:  getdiffLevels
        *Purpose: Get the parent category list 
 	*/
	public function getdiffLevels(){
		$this->db->select('difficulty_levels_id,difficulty_levels_title,preference');
		$this->db->from(TBL_DIFFICULTY_LEVELS);
		//$this->db->where('question_category_id=parent_category_id',NULL,false);
		$this->db->order_by('preference','asc');
		$this->db->where('is_active',1);
		$this->db->where('is_deleted',0);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows())
			return $result->result();
		return false;
	}

	/*
	*Methodname:  checkExistDiffLevel
        *Purpose: function check existing difficulty levels
 	*/	
	
	public function checkExistDiffLevel($diffLevelTitle,$diffLevelId) {
		if(empty($diffLevelTitle) || empty($diffLevelId))
			return false;
		
		$this->db->select('count(*) as total');
		if($this->db->where('difficulty_levels_title',$diffLevelTitle)->where('difficulty_levels_id',$diffLevelId)->get(TBL_DIFFICULTY_LEVELS)->row()->total==0)
			return false;
		return true;
	}
	
	/*
	*Methodname:  editDiffLevels
        *Purpose: save difficulty levels
 	*/	
	public function editDiffLevels($formData,$diffId){
		$this->db->where('difficulty_levels_id',$diffId);
		if($this->db->update(TBL_DIFFICULTY_LEVELS,$formData))
			return true;
		return false;
	}
	/*
	*Methodname:  updateDifficultyDetail
        *Purpose: update difficulty level sort order details
 	*/	
	
	public function updateDifficultyDetail($tableName,$pkId,$diffId,$sort) {
		$this->db->where($pkId,$diffId);
		//$this->db->set('preference',$sort);
		if($this->db->update($tableName,$sort))
			return true;
		return false;
	}
	/*
	*Methodname:  addRemoveQuestions
        *Purpose: save and remove questions categories
 	*/	
	public function addRemoveQuestions($action,$questionsId,$categoryId) {
		
		$QuesIds = explode(',',$questionsId);
		if($action=='checked') {
			foreach($QuesIds as $questionsId) {
				$query = 'UPDATE '.TBL_QUESTION.' SET sub_categories= if( sub_categories IS NULL
OR sub_categories = "",'.$categoryId.',concat(sub_categories,",",'.$categoryId.')) where !find_in_set('.$categoryId.',sub_categories) AND questions_id='.$questionsId.'';
				$this->db->query($query);
			}
			//echo $this->db->last_query();
			return true;
		} else if($action=='unchecked') {
			foreach($QuesIds as $questionsId) {
				$query = 'update `'.TBL_QUESTION.'` set `sub_categories`=IF(FIND_IN_SET('.$categoryId.',sub_categories)=1,SUBSTRING(sub_categories, LENGTH(concat('.$categoryId.',","))+1),replace( CONCAT("",sub_categories,""), CONCAT(",",'.$categoryId.',","), ",")),`sub_categories`=replace(sub_categories,CONCAT(",",'.$categoryId.'),"") WHERE find_in_set('.$categoryId.',sub_categories) AND questions_id='.$questionsId.'';
				$this->db->query($query);
			}
			//echo $this->db->last_query();
			return true;
		}
		return false;
	}
	/*
	*Methodname:  removeCategQues
        *Purpose: remove questions categories
 	*/	
	public function removeCategQues($questionId,$categoryId) {
		$query = 'update `'.TBL_QUESTION.'` set `sub_categories`=IF(FIND_IN_SET('.$categoryId.',sub_categories)=1,SUBSTRING(sub_categories, LENGTH(concat('.$categoryId.',","))+1),replace( CONCAT("",sub_categories,""), CONCAT(",",'.$categoryId.',","), ",")),`sub_categories`=replace(sub_categories,CONCAT(",",'.$categoryId.'),"") WHERE find_in_set('.$categoryId.',sub_categories) AND questions_id='.$questionId.'';
		if($this->db->query($query))
			return true;
		return false;
	}
	/*
	*Methodname:  saveQuesSubcategories
        *Purpose: To check category name
 	*/	
	public function saveQuesSubcategories($mainCategory,$subCategoriesAll) {
		$uniqueCategories = '';
		foreach($subCategoriesAll as $key=>$data) {
			$keyId = explode('_',$key);
			$keyId = $keyId[count($keyId)-1]; 
			$query = 'SELECT group_concat(`questions_id`) as questions_id  FROM `tbl_questions` WHERE find_in_set('.$keyId.',`sub_categories`)  AND !find_in_set('.$mainCategory.',`sub_categories`)';
			$result = $this->db->query($query);
			if($result->num_rows()) {
				if($uniqueCategories=='')
					$uniqueCategories = $result->row()->questions_id;
				else 
					$uniqueCategories .= ','.$result->row()->questions_id;
			}
		}
		if($uniqueCategories!='') {
			$uniqueCategories =  array_unique(explode(',',$uniqueCategories));
		}
		if(is_array($uniqueCategories)) {
			foreach($uniqueCategories as $questionsId) {
				$query = 'UPDATE '.TBL_QUESTION.' SET sub_categories= if( sub_categories IS NULL
	OR sub_categories = "",'.$mainCategory.',concat(sub_categories,",",'.$mainCategory.')) where !find_in_set('.$mainCategory.',sub_categories) AND questions_id='.$questionsId.'';
				$this->db->query($query);
			}
		}
		return true;
		
	}
	/*
	*Methodname:  getCategories
        *Purpose: Get the category list to grid
 	*/
	
	public function getCategories($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT quesCat.question_category_id,quesCat.category_title,quesCat.test_duration,quesCat.total_marks,quesCat.no_of_questions,quesCat.difficulty_levels,quesCat.created_date,quesCat.updated_date,quesCat.is_active,prntQuesCat.category_title AS parent_category_title FROM '.TBL_QUESTION_CATEGORY.' as quesCat LEFT JOIN '.TBL_QUESTION_CATEGORY.' as prntQuesCat ON quesCat.parent_category_id=prntQuesCat.question_category_id WHERE quesCat.is_deleted=0 AND quesCat.question_category_id != '.PARENT_CATEGORY.' AND quesCat.question_category_id !='.DEFAULT_CATEGORY;
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
								$val['data'] = str_replace ("/","-",$val['data']);
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
	*Methodname:  deleteQuesCategory
        *Purpose: delete category
 	*/
	
	public function deleteQuesCategory($categoryId) {
		$this->db->set('is_deleted',1);
		$this->db->set('is_active',0);
		$this->db->where('question_category_id',$categoryId);
		$this->db->update(TBL_QUESTION_CATEGORY);
		if($this->db->affected_rows()) {
			$this->db->set('updated_date',date('Y-m-d H:i:s'));
			$this->db->where('question_category_id',$categoryId);
			$this->db->update(TBL_QUESTION_CATEGORY);
			return true;
		}
		return false;
	}
	/*
	*Methodname:  filterQuesByCategory
        *Purpose: Get the questions by category
 	*/
	public function filterQuesByCategory($params = "" , $page = "all", $count=false){
		$sql = 'SELECT questions_id,question_description,answer_type,no_of_options,difficulty_levels_title,questions.created_date,questions.updated_date,IF(FIND_IN_SET('.$params['mainCategoryId'].',`sub_categories`)>0,questions_id,NULL) as selectStatus FROM '.TBL_QUESTION.' AS questions INNER JOIN '.TBL_DIFFICULTY_LEVELS.' AS difficulty_levels ON questions.fk_difficulty_levels_id=difficulty_levels.preference  WHERE questions.is_active=1 AND FIND_IN_SET('.$params['categoryId'].',sub_categories) ';
		
		//
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
								$val['data'] = str_replace ("/","-",$val['data']);
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
	*Methodname:  diffLevels
        *Purpose: Get the category list to grid
 	*/
	public function diffLevels($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT * FROM '.TBL_DIFFICULTY_LEVELS.' WHERE 1';
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
								$val['data'] = str_replace ("/","-",$val['data']);
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
	*Methodname:  checkCategoryName
        *Purpose: To check category name
 	*/
	public function checkCategoryName($categoryName,$questionCategoryId='') {
		if(empty($questionCategoryId)){
			$query = $this->db->query('SELECT `category_title` FROM `'.TBL_QUESTION_CATEGORY.'`  WHERE `category_title`="'.$this->db->escape_str(trim($categoryName)).'"');
		}else{
			$query = $this->db->query('SELECT `category_title` FROM `'.TBL_QUESTION_CATEGORY.'`  WHERE `category_title`="'.$this->db->escape_str(trim($categoryName)).'" AND question_category_id!= "'.$questionCategoryId.'"');	
		}
		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	*Methodname:  getCategoryDetails
        *Purpose: get questions category basic details
	*/
	public function getCategoryDetails($categoryId){
		$this->db->select('question_category.question_category_id,question_category.category_title,parent_category.category_title as parent_category_title');
		$this->db->join(TBL_QUESTION_CATEGORY.' AS parent_category','question_category.parent_category_id=parent_category.question_category_id','left');
		$this->db->where('question_category.question_category_id',$categoryId);
		//$this->db->where('question_category.parent_category_id is not null',NULL,false);
		$result = $this->db->get(TBL_QUESTION_CATEGORY.' AS question_category');
		if($result->num_rows())
			return $result->row();
		return false;
	}
	
/*
	*Methodname:  getCategoryQuestionAns
        *Purpose: Get the category list to grid
*/
	public function getCategoryQuestionAns($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT answer_id,answer_description,is_correct,questions_id,questions.question_description,diffLevels.difficulty_levels_title,questions.created_date,questions.updated_date,questions.is_active FROM '.TBL_ANSWER.' as answers INNER JOIN '.TBL_QUESTION.' questions ON  answers.fk_questions_id=questions.questions_id INNER JOIN '.TBL_DIFFICULTY_LEVELS.' as diffLevels ON questions.fk_difficulty_levels_id=diffLevels.preference WHERE find_in_set('.$params['categoryId'].',questions.sub_categories)>0';
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
								$val['data'] = str_replace ("/","-",$val['data']);
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
	*Methodname:  getSubCategories
        *Purpose: get all subcategories
	*/
	public function getSubCategories($categoryId,$withCategory=false) {
		$this->db->select('question_category.question_category_id,question_category.category_title');
		$this->db->where('question_category.parent_category_id is not null',NULL,false);
		if($withCategory) {
			$this->db->where('question_category.question_category_id',$categoryId);
		} else {
			$this->db->where('question_category.question_category_id !=',$categoryId);
		}
		$result = $this->db->get(TBL_QUESTION_CATEGORY.' AS question_category');
		if($result->num_rows())
			return $result->result();
		return false;
	}
	
	/*
	*Methodname:  getQuestionCategoryData
        *Purpose: get questions category
	*/
	public function getQuestionCategoryData($categoryId) {
		$this->db->select('*');
		$this->db->where('question_category_id',$categoryId);
                $result = $this->db->get(TBL_QUESTION_CATEGORY);
		if($result->num_rows())
			return $result->row();
		return false;
	}
	
	/*
	*Methodname:  checkDiffLevels
        *Purpose: To check category name
	*/
	public function checkDiffLevels($diffLevelTitle,$opt,$id) {
		if($opt == 'add'){
			$query = $this->db->query('SELECT `difficulty_levels_title` FROM `'.TBL_DIFFICULTY_LEVELS.'`  WHERE `difficulty_levels_title`="'.$this->db->escape_str(trim($diffLevelTitle)).'"');
		}else{
			$query = $this->db->query('SELECT `difficulty_levels_title` FROM `'.TBL_DIFFICULTY_LEVELS.'`  WHERE `difficulty_levels_title`="'.$this->db->escape_str(trim($diffLevelTitle)).'" AND difficulty_levels_id!= '.$id);	
		}
		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}
	
	
	
	/*
	*Methodname: addEditCategory
        *Purpose: To add category 
	*/
	public function addEditCategory($postArray,$quesCategoryId=''){
		if(empty($quesCategoryId)){
			if($this->db->insert(TBL_QUESTION_CATEGORY, $postArray)){
			 	return $this->db->insert_id();
			}
		} else {
			$this->db->where('question_category_id',$quesCategoryId);
			if($this->db->update(TBL_QUESTION_CATEGORY, $postArray)){
			 	return $quesCategoryId;
			}
		}
		return false;
	}
	
	/*
	*Methodname:  addDiffLevel
        *Purpose: To add Diff Level 
	*/
	public function addDiffLevel($postArray){
		//$this->db->set('preference','(MAX(`preference`)+1)',false);
		$this->db->select('max(preference) as preference');
		$this->db->from(TBL_DIFFICULTY_LEVELS);
		$result = $this->db->get();
		$preference = 1;
		if($result->num_rows())
			$preference = $result->row()->preference+1;
		
		$this->db->set('preference',$preference);
		if($this->db->insert(TBL_DIFFICULTY_LEVELS, $postArray)){
			 return $this->db->insert_id();
		}
		return false;
	}
	
	
	
	/*
	*Methodname: updateParentCategoryId
        *Purpose: To update parent category id category 
	*/
	public function updateParentCategoryId($categoryId) {
		$this->db->set('parent_category_id',$categoryId);
		$this->db->where('question_category_id',$categoryId);
		if($this->db->update(TBL_QUESTION_CATEGORY))
			return true;
		return false;		
 	}
	
        
        
        /*
	*Methodname:  checkDiffLevels
        *Purpose: To check category name
	*/
	public function checkPromocode($promocodeTitle,$promocodeId) {
                if(empty($promocodeId))                                
	        $query = $this->db->query('SELECT `promocode_title` FROM `'.TBL_PROMOCODE.'`  WHERE `promocode_title`="'.$this->db->escape_str(trim($promocodeTitle)).'"');
		else
                $query = $this->db->query('SELECT `promocode_title` FROM `'.TBL_PROMOCODE.'`  WHERE `promocode_title`="'.$this->db->escape_str(trim($promocodeTitle)).'" AND promocode_id!= "'.$promocodeId.'"');    
                if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
            
        }
        
        /*
	*Methodname:  checkDiffLevels
        *Purpose: To check category name
	*/
	public function checkPromocodeById($promocodeId) {
	
	        $query = $this->db->query('SELECT promocode_id,promocode_title,discount_type,discount,no_of_use,expiry_date,is_active FROM `'.TBL_PROMOCODE.'`  WHERE `promocode_id` = "'.$this->db->escape_str(trim($promocodeId)).'"');
		if($query->num_rows() > 0) 
			return $query->row();
		return false;
		
	}                                         
                
     	/*
	*Methodname:  addPromocode
        *Purpose: To add promocode  
	*/
	public function addPromocode($postArray){
		
		if($this->db->insert(TBL_PROMOCODE, $postArray)){
			 return $this->db->insert_id();
		}
		return false;
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

/* End of file configuration_model.php */
/* Location: ./application/model/admin/configuration_model.php */