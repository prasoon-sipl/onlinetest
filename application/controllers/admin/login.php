<?php
/*
* Version       : 1.0
* Filename      : login.php
* Purpose       : This class is will handle admin login functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->library('template');
        $this->load->model(ADMIN . 'login_model');
    }
    /*
    *Methodname:  index
    *Purpose: Dispaly login page
    */
    public function index() {
        if ($this->session->userdata('adminId')) redirect(ADMIN . 'dashboard');
        $this->template->title = 'Admin Login - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'login/login_view', '', true);
        $this->template->renderAdminLoginTemplate();
    }
    /*
    *Methodname:  login
    *Purpose: this function to check user login
    */
    public function checkLogin() {
        /* Load Libraries */
        $this->load->library('form_validation');
        $this->_setLoginFormRequiredField();
        if ($this->form_validation->run() == FALSE) {
            $this->template->title = 'Admin Login';
            $this->template->content = $this->load->view(ADMIN . 'login/login_view', '', true);
            $this->template->renderAdminLoginTemplate();
        } else {
            //login_message
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
            $result = $this->login_model->checkUserNamePass($email);
            if ($result) {
                //check password
                $this->load->library('phpass'); //load hash pass lib
                if ($this->phpass->check($password, $result->password)) {
                    //check if account is acitve
                    if ($result->is_active == 1) {
                        $sessionData = array('adminEmail' => $result->email, 'adminName' => $result->full_name, 'adminId' => $result->user_id);
                        $this->session->set_userdata($sessionData);
                        redirect('admin/dashboard');
                    } else {
                        $this->session->set_flashdata('errorMsg', 'Admin account is inactive.');
                        redirect(ADMIN . 'login');
                    }
                } else {
                    $this->session->set_flashdata('errorMsg', 'Incorrect email address or password.');
                    redirect(ADMIN . 'login');
                }
            } else {
                $this->session->set_flashdata('errorMsg', 'Incorrect email address or password.');
                redirect(ADMIN . 'login');
            }
        }
    }
    /*
    *Methodname:  _setLoginFormRequiredField
    *Purpose: this function to apply validation
    */
    private function _setLoginFormRequiredField() {
        $validationField = array(array('field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email'), array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),);
        $this->form_validation->set_rules($validationField);
        $this->form_validation->set_message('required', 'Required');
        $this->form_validation->set_message('valid_email', 'Please provide valid email address.');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
    }
    /*
    *Methodname:  logout
    *Purpose: To logout user
    */
    public function logout() {
        $this->session->sess_destroy();
        redirect('/');
    }
}
/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */