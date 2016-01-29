<?php
/*
 * Version       : 1.0
 * Filename      : settings_model.php
 * Purpose       : This class is will handle database admin setting.
 */
class Settings_model extends CI_model {
	
	/*
	*Methodname:  __construct
        *Purpose: Perform common action for class at load 
 	*/
	function __construct()	{
		parent:: __construct();
	}
	
	
	/*
	*Methodname:  getAdminDetails
        *Purpose: Get admin details
 	*/
	function getAdminDetails(){
		$this->db->select('*');		
		$this->db->where('user_id', $this->session->userdata('adminId'));
		$this->db->where('role', 'admin');
		$query = $this->db->get(TBL_TEST_USER);
		if($query->num_rows())
			return $query->row();
		else
			return false;
	}
	
	/*
	*Methodname:  updateDetail
        *Purpose:  To update details
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
	*Methodname:  checkEmail
        *Purpose:  To check unique email
 	*/
	public function checkEmail($email){
		$this->db->where('email', $this->db->escape_str(trim($email)));
		$this->db->where_not_in('user_id', $this->session->userdata('adminId'));
		$result = $this->db->get(TBL_TEST_USER);
		if ($result->num_rows() > 0 )
			return 1;
		else
			return 0;
	}

 }

/* End of file settings_model.php */
/* Location: ./application/model/admin/settings_model.php */