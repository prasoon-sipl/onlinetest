<?php
/*
* Version       : 1.0
* Filename      : login.php
* Purpose       : This class is will handle login functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->helper('cookie');
        $this->load->library('encrypt');
        $this->load->model('login_model');
    }
    /*
    *Methodname:  index
    *Created Date: 10-July-2014
    */
    public function index() {
        redirect('/');
    }
    /*
    *Methodname:  loginAjax
    *Purpose: this function to check user login
    */
    public function loginAjax() {
        /* Load Libraries */
        $this->load->library('form_validation');
        $this->_setLoginFormRequiredField();
        $result = array();
        $result['status'] = false;
        if ($this->form_validation->run() == FALSE) {
            $error = array('password' => form_error('password'), 'user_email' => form_error('user_email'));
            $result['message'] = $error;
        } else {
            $userEmail = $this->input->post('user_email', TRUE);
            $password = $this->input->post('password', TRUE);
            $KeepMeLogin = $this->input->post('keep_me_login', TRUE);
            $result = $this->login_model->checkUserNamePass($userEmail);
            if ($result) {
                //check password
                $this->load->library('phpass');
                if ($this->phpass->check($password, $result->password)) {
                    //check if account is acitve
                    if ($result->is_active == 1) {
                        //set cookie for login user if keep me login checked
                        $response['status'] = true;
                        $sessionData = array('testSesUserId' => $result->user_id, 'userEmail' => $result->email, 'userFullName' => $result->full_name);
                        $this->session->set_userdata($sessionData);
                        if (!empty($KeepMeLogin)) {
                            $value = $this->encrypt->encode(implode('|', array($userEmail, $this->input->post('password', TRUE))));
                            set_cookie('user_auth_token', $value, (86400 * 7));
                        }
                    } else {
                        // when user id and password does not match
                        $response['message'] = array('login_message' => '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>Your account is not active, please contact Administrator to activate account.</div>');
                    }
                } else {
                    // when user id and password does not match
                    $response['message'] = array('login_message' => '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>Incorrect email address or password.</div>');
                }
            } else {
                // when user id and password does not match
                $response['message'] = array('login_message' => '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>Incorrect email address or password.</div>');
            }
        }
        echo json_encode($response);
    }
    /*
    *Methodname:  _setLoginFormRequiredField
    *Purpose: this function to apply validation
    */
    private function _setLoginFormRequiredField() {
        $validattionField = array(array('field' => 'user_email', 'label' => 'Email Address', 'rules' => 'required|trim|valid_email|max_length[100]'), array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|max_length[15]||min_length[6]'),);
        $this->form_validation->set_rules($validattionField);
        $this->form_validation->set_message('required', 'Required');
        $this->form_validation->set_message('valid_email', 'Please provide valid email address');
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
/* Location: ./application/controllers/login.php */