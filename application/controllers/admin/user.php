<?php
/*
* Version       : 1.0
* Filename      : user.php
* Purpose       : This class is will handle admin user activity.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        //Check admin session id is unset and redirct site index page
        if (!$this->session->userdata('adminId')) redirect('/');
        //Load model class
        $this->load->library('template');
        $this->load->model(ADMIN . 'user_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display user information and give option to add user
    */
    public function index() {
        //Jqgrid
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
        //Jqgrid
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.js'));
        $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
        //Bootstrap datepikcer js
        $data['userData'] = $this->user_model->getUserDetails();
        $this->template->title = 'User - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'user/user_view', $data, true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname: userGrid
    *Purpose: Display user grid
    */
    public function userGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->user_model->getUser($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->user_model->getUser($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  checkUserEmail
    *Purpose: check user email before create new
    */
    public function checkUserEmail() {
        $userEmail = $this->input->post('email', TRUE);
        $userId = $this->input->post('user_id', TRUE);
        if ($this->user_model->checkUserEmail($userEmail, $userId)) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  addUser
    *Purpose: Add user
    */
    public function addUser() {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            $this->result['status'] = false;
            $this->result['message'] = 'Invalid request!';
            echo json_encode($this->result);
            exit(0);
        }
        $this->load->library('form_validation');
        $validattionField = array(array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|trim|is_unique[' . TBL_TEST_USER . '.email]|min_length[2]|max_length[100]'), array('field' => 'full_name', 'label' => 'Required', 'rules' => 'required|trim'), array('field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]|max_length[15]|trim|callback_validatePassword'));
        $this->form_validation->set_rules($validattionField);
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('is_unique', 'User already exists.');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $error = array();
            $error['email'] = form_error('email');
            $error['full_name'] = form_error('full_name');
            $error['password'] = form_error('password');
            $this->result['error']['formerror'] = $error;
            $this->result['message'] = 'Incorrect form input.';
        } else {
            $this->load->library('phpass');
            $postArray['password'] = $this->phpass->hash($this->input->post('password', TRUE));
            $postArray['email'] = $this->input->post('email', true);
            $postArray['full_name'] = $this->input->post('full_name', true);
            $postArray['created_date'] = date('Y-m-d H:i:s');
            $postArray['updated_date'] = date('Y-m-d H:i:s');
            $postArray['role'] = 'user';
            if ($this->user_model->addDetails(TBL_TEST_USER, $postArray)) {
                $this->result['message'] = 'user added successfully!!';
                $this->result['status'] = true;
            } else $this->result['message'] = 'An error was encountered please try again later.';
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  validatePassword
    *Purpose: validation for password
    */
    public function validatePassword($rpassword) {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $rpassword)) {
            return TRUE;
        } else {
            $this->form_validation->set_message("validatePassword", "Password must contain at least 1 special character.");
            return FALSE;
        }
    }
    /*
    *Methodname:  userEdit
    *Purpose: edit user
    */
    public function userEdit() {
        try {
            $userId = $this->uri->segment(4, "");
            if (!$userId) throw new Exception('Wrong inputs!');
            $userDetail = $this->user_model->getUserDetails($userId);
            $data['userData'] = $this->user_model->getUserDetails();
            if ($userDetail) {
                $data['userDetail'] = $userDetail;
                $this->template->title = 'User Edit - ' . SITE_NAME;
                $this->template->content = $this->load->view(ADMIN . 'user/user_edit_view', $data, true);
                $this->template->renderAdminTemplate();
            } else {
                throw new Exception('Wrong inputs!');
            }
        }
        catch(Exception $e) {
            $data['error'] = 'No data found';
            $this->template->title = 'No data found';
            $this->template->content = $this->load->view(ADMIN . 'error_view', $data, true);
            $this->template->renderAdminTemplate();
        }
    }
    /*
    *Methodname:  editUser
    *Purpose: edit user information
    */
    public function editUser() {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            $this->result['status'] = false;
            $this->result['message'] = 'Invalid request!';
            echo json_encode($this->result);
            exit(0);
        }
        $this->load->library('form_validation');
        $validattionField = array(array('field' => 'user_id', 'label' => 'Required', 'rules' => 'required|trim|numeric'), array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|trim|min_length[2]|max_length[100]'), array('field' => 'full_name', 'label' => 'Required', 'rules' => 'required|trim'), array('field' => 'is_active', 'label' => 'Is active', 'rules' => 'required|trim|numeric'));
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]|max_length[15]|trim|callback_validatePassword');
        }
        $this->form_validation->set_rules($validattionField);
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('is_unique', 'User already exists!');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $error = array();
            $error['user_id'] = form_error('user_id');
            $error['email'] = form_error('email');
            $error['full_name'] = form_error('full_name');
            $error['password'] = form_error('password');
            $this->result['error']['formerror'] = $error;
            $this->result['message'] = 'Incorrect form input.';
        } else {
            if ($this->input->post('password')) {
                $this->load->library('phpass');
                $postArray['password'] = $this->phpass->hash($this->input->post('password', TRUE));
            }
            $postArray['email'] = $this->input->post('email', true);
            $postArray['full_name'] = $this->input->post('full_name', true);
            $postArray['is_active'] = $this->input->post('is_active', true);
            $userId = $this->input->post('user_id', true);
            if ($this->user_model->checkUserEmail($postArray['email'], $userId)) {
                $error = array();
                $error['discount'] = "Email already exists!";
                $this->result['error']['formerror'] = $error;
                $this->result['message'] = 'Incorrect form input.';
            } elseif ($this->user_model->updateDetail(TBL_TEST_USER, 'user_id', $userId, $postArray)) {
                $this->result['message'] = 'User information update successfully!!';
                $this->session->set_flashdata('successMsg', 'User has been saved successfully.');
                $this->result['status'] = true;
            } else $this->result['message'] = 'Some problem occured! We can\'t save';
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  deleteUser
    *Purpose: delete user
    */
    public function deleteUser($userId = '') {
        if (empty($userId)) {
            $this->session->set_flashdata('errorMsg', 'User doesn\'t exists.');
            redirect(ADMIN . '/user');
        }
        $postArray['is_deleted'] = 1;
        if ($this->user_model->updateDetail(TBL_TEST_USER, 'user_id', $userId, $postArray)) {
            $this->session->set_flashdata('successMsg', 'User has been deleted successfully.');
            redirect(ADMIN . 'user');
        } else {
            $this->session->set_flashdata('errorMsg', 'User can not be deleted.');
            redirect(ADMIN . 'user');
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */