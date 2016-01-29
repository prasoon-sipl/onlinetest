<?php
/*
 * Version       : 1.0
 * Filename      : questions_model.php
 * Purpose       : This class is will handle database function of configuration view.
 */
class Questions_model extends CI_model {
	public $errorMessage;
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
	public function getQuestionsSubCategory(){
		$this->db->select('question_category_id,category_title');
		$this->db->from(TBL_QUESTION_CATEGORY);
		//$this->db->where('question_category_id=parent_category_id',NULL,false);
		$this->db->where('parent_category_id IS NOT NULL',NULL,false);
		$this->db->where('is_active',1);
		$this->db->where('is_deleted',0);
		$result = $this->db->get();
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
	*Methodname:  saveQuestionAnswer
        *Purpose: save questions and answers 
        */
	public function saveQuestionAnswer($questioData,$answer,$correctAns) {
		try {
			if(!is_array($questioData))
				throw new Exception('Invalid data');
				
			$this->db->trans_begin();
			$this->db->insert(TBL_QUESTION,$questioData);
			$questionId = $this->db->insert_id();
			if(empty($questionId))
				throw new Exception('Question can not be created!');
			
			$ansTableData = array();
			foreach($answer as $key=>$data) {
				$ansTableData[$key]['fk_questions_id'] = $questionId;
				$ansTableData[$key]['answer_description'] = $data;
				if(is_array($correctAns))
					$ansTableData[$key]['is_correct'] = in_array(($key+1),$correctAns)?1:0;
				else if(($key+1)==$correctAns)
					$ansTableData[$key]['is_correct'] = 1;
				else 
					$ansTableData[$key]['is_correct']=0;
				$ansTableData[$key]['created_date'] = date('Y-m-d H:i:s');
				$ansTableData[$key]['updated_date'] = date('Y-m-d H:i:s');
			}
			if($this->db->insert_batch(TBL_ANSWER, $ansTableData)){
				$this->db->trans_commit();
				return true;
			} else
				throw new Exception('Some error occured, Please try again!');
		}
		catch(Exception $e) {
			$this->errorMessage = $e->getMessage();
			$this->db->trans_rollback();
			return false;
		}
	}
	
	
	public function updateQuestionAnswer($questionId,$questioData,$answer,$correctAns) {
		//updateQuestionAnswer
		try {
			if(!is_array($questioData))
				throw new Exception('Invalid data');
				
			$this->db->trans_begin();
			$this->db->where('questions_id',$questionId);
			
			if(!$this->db->update(TBL_QUESTION,$questioData))
				throw new Exception('Question can not be created!');
			
			/* delete previous rows from answer table */
			$this->db->where('fk_questions_id',$questionId);
			if(!$this->db->delete(TBL_ANSWER)) 
				throw new Exception('Unable to modify answers!');
			/* now insert new rows in answer table */
			
			$ansTableData = array();
			foreach($answer as $key=>$data) {
				$ansTableData[$key]['fk_questions_id'] = $questionId;
				$ansTableData[$key]['answer_description'] = $data;
				if(is_array($correctAns))
					$ansTableData[$key]['is_correct'] = in_array(($key+1),$correctAns)?1:0;
				else if(($key+1)==$correctAns)
					$ansTableData[$key]['is_correct'] = 1;
				else 
					$ansTableData[$key]['is_correct']=0;
				$ansTableData[$key]['created_date'] = date('Y-m-d H:i:s');
				$ansTableData[$key]['updated_date'] = date('Y-m-d H:i:s');
			}
			if($this->db->insert_batch(TBL_ANSWER, $ansTableData)){
				$this->db->trans_commit();
				return true;
			} else
				throw new Exception('Some error occured, Please try again!');
		}
		catch(Exception $e) {
			$this->errorMessage = $e->getMessage();
			$this->db->trans_rollback();
			return false;
		}
	
	}
	
	/*
	*Methodname:  getQuestionData
        *Purpose: Get the questions data based on id
        */
	public function getQuestionData($questionId){
		$this->db->select('questions.questions_id,questions.fk_difficulty_levels_id,questions.sub_categories,questions.question_title,questions.answer_type,questions.no_of_options,questions.question_description,questions.marks,questions.is_active,answers.answer_description,answers.is_correct');
		$this->db->from(TBL_ANSWER.' as answers');
		$this->db->join(TBL_QUESTION.' as questions','answers.fk_questions_id=questions.questions_id','inner');
		$this->db->where('answers.fk_questions_id',$questionId);
		$result = $this->db->get();
		if($result->num_rows())
			return $result->result();
		return false;
	}
		
	/*
	*Methodname:  getQuestionAns
        *Purpose: Get the category list to grid
        */
	public function getQuestionAns($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT answer_id,answer_description,is_correct,questions_id,questions.question_description,diffLevels.difficulty_levels_title,questions.created_date,questions.updated_date,questions.is_active FROM '.TBL_ANSWER.' as answers INNER JOIN '.TBL_QUESTION.' questions ON  answers.fk_questions_id=questions.questions_id INNER JOIN '.TBL_DIFFICULTY_LEVELS.' as diffLevels ON questions.fk_difficulty_levels_id=diffLevels.preference WHERE 1';
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
		//echo $this->db->last_query();
		return $query;
	}
	
	/*
	*Methodname:  getCategories
        *Purpose: Get the category list to grid
        */
	public function getQuestions($params = "" , $page = "all", $count=false) {
		$sql = 'SELECT questions_id,questions.question_description,diffLevels.difficulty_levels_title,questions.created_date,questions.updated_date,questions.is_active FROM '.TBL_QUESTION.' as questions INNER JOIN '.TBL_DIFFICULTY_LEVELS.' as diffLevels ON questions.fk_difficulty_levels_id=diffLevels.preference WHERE 1';
		
		if(!empty($params['categoryId']))
			$sql.=' AND find_in_set('.$params['categoryId'].',questions.sub_categories)>0 ';
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
		//echo $this->db->last_query();
		return $query;
	}
	/*
	*Methodname:  getAnswers
        *Purpose: return answers bases of question id
        */
	
	public function getAnswers($questionId) {
		if(empty($questionId))
			return false;
		$this->db->select('answer_id,is_correct,answer_description,is_active');
		$this->db->from(TBL_ANSWER);
		$this->db->where('fk_questions_id',$questionId);
		$result = $this->db->get();
 		if($result->num_rows()) {
			$response = array();
			foreach($result->result() as $key=>$data) {
				$response[] = array('id'=>$key,'cell'=>array($data->answer_id,$data->answer_description,$data->is_correct,$data->is_active));
			}
			//print_r($response);
			return $response; 
		}
			
		return false;
	}
	/*
	*Methodname:  checkCategoryName
        *Purpose: To check category name
        */
	public function checkCategoryName($categoryName,$opt,$id) {
		if($opt == 'add'){
			$query = $this->db->query('SELECT `category_title` FROM `'.TBL_QUESTION_CATEGORY.'`  WHERE `category_title`="'.$this->db->escape_str(trim($categoryName)).'"');
		}else{
			$query = $this->db->query('SELECT `category_name` FROM `'.TBL_QUESTION_CATEGORY.'`  WHERE `category_title`="'.$this->db->escape_str(trim($categoryName)).'" AND question_category_id!= '.$id);	
		}
		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	*Methodname:  addCategory
        *Purpose: To add category 
        */
	public function addCategory($postArray){
		if($this->db->insert(TBL_QUESTION_CATEGORY, $postArray)){
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
	*Methodname:  checkCatName
        *Purpose: To check category name
        */
	public function checkCatName($categoryName) {
		
		$query = $this->db->query('SELECT `question_category_id` FROM `'.TBL_QUESTION_CATEGORY.'`  WHERE `category_title`="'.$this->db->escape_str(trim($categoryName)).'" AND parent_category_id IS NOT NULL AND is_active=1 AND is_deleted=0');	
		if($query->num_rows() > 0) {
			return $query->row();
		}else{
			return false;
		}
	}
        /*
	*Methodname:  checkDiffLevel
        *Purpose: To check DiffLevel
        */
	public function checkDiffLevel($diffLevelName) {
		
		$query = $this->db->query('SELECT `difficulty_levels_id` FROM `'.TBL_DIFFICULTY_LEVELS.'`  WHERE `difficulty_levels_title`="'.$this->db->escape_str(trim($diffLevelName)).'" AND is_active=1 AND is_deleted=0');	
		if($query->num_rows() > 0) {
			return $query->row();
		}else{
			return false;
		}
	}
        
        /*
	*Methodname:  checkQuestion
        *Purpose: To check Question 
        */
	public function checkQuestion($question,$categoryId) {
		
		$query = $this->db->query('SELECT `questions_id` FROM `'.TBL_QUESTION.'`  WHERE question_description= "'.$question.'" AND FIND_IN_SET( '.$categoryId.', `sub_categories` ) >0 AND is_active=1 AND is_deleted=0');	
		if($query->num_rows() > 0) {
			return $query->row();
		}else{
			return false;
		}
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
        
        /*
	*Methodname:  deleteError
        *Purpose: delete errors 
        */
	public function deleteError($adminId){
		$this->db->where('fk_admin_id', $adminId);
                $this->db->delete(TBL_QUESTION_IMPORT_ERROR); 
	}
        
        
        /*
	*Methodname:  getQuestionsError
        *Purpose: get questions error
        */
	public function getQuestionsError($adminId){
		 $query = $this->db->query('SELECT `question_category`,question_level,question_type,question,option1,option2,option3,option4,option5,answers,error_string FROM `'.TBL_QUESTION_IMPORT_ERROR.'`  WHERE fk_admin_id= '.$adminId);	
		if($query->num_rows() > 0) {
			return $query->result();
		}else{
			return false;
		}
	}
        
}

/* End of file questions_model.php */
/* Location: ./application/model/admin/questions_model.php */