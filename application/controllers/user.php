<?php
/*
* Version       : 1.0
* Filename      : user.php
* Purpose       : This class is will handle user activity.
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
        if ($this->session->userdata('testSesUserId')) redirect('dashboard');
        //Load model class
        $this->load->library('template');
        $this->load->model('user_model');
    }
    /*
    *Methodname:  index
    */
    public function index() {
        $this->getUserData();
    }
    /*
    *Methodname:  getUserData
    */
    public function getUserData() {
        if ($this->input->post('user_post')) {
            try {
                //$_SERVER['HTTP_REFERER']
                $this->load->library('form_validation');
                $validattionField = array(array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|trim|min_length[2]|max_length[100]'), array('field' => 'full_name', 'label' => 'Full Name', 'rules' => 'required|trim|min_length[2]|max_length[100]'), array('field' => 'user_id', 'label' => 'User Id', 'rules' => 'required|trim|numeric'));
                $this->form_validation->set_rules($validattionField);
                $this->form_validation->set_message('required', 'Required');
                $this->form_validation->set_message('max_length', '%s should not be greater than %s characters.');
                $this->form_validation->set_message('min_length', '%s should not be less than %s characters.');
                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == FALSE) {
                    //                       $error = array();
                    //            $error['email']=form_error('email');
                    //                        $error['full_name']=form_error('full_name');
                    //                        $error['user_id']=form_error('user_id');
                    //                       print_r($error);
                    throw new Exception("An error occured while validating data, please try again later or contact with site admin.");
                } else {
                    // Validating post data and set action accordingly
                    $email = $this->input->post('email', true);
                    $fullName = $this->input->post('full_name', true);
                    $userId = $this->input->post('user_id', true);
                    // Check Session with mainSite Sessssions
                    if (!$this->session->userdata('isCreator')) throw new Exception("An error occured while validating data, please try again later or contact with site admin.");
                    if ($this->session->userdata('userEmail') != $email || $this->session->userdata('userFullName') != $fullName || $this->session->userdata('userId') != $userId) {
                        throw new Exception("An error occured while validating data, please try again later or contact with site admin.");
                    }
                    // Check email and userid in test user tbl
                    $userDetails = $this->user_model->checkUserEmail($email, $userId);
                    if ($userDetails) {
                        // Set login sesstion
                        $sessionData = array('testSesUserId' => $userDetails->user_id, 'userEmail' => $userDetails->email, 'userFullName' => $userDetails->full_name);
                        $this->session->set_userdata($sessionData);
                        $this->user_model->saveActivityLog($userDetails->fk_user_id, TEST_LOGIN, 'test login, test user id=' . $userDetails->user_id, TBL_TEST_USER, '');
                        redirect('dashboard');
                    } else {
                        //Check email
                        $userDetails = $this->user_model->checkUserEmail($email, '');
                        if ($userDetails) throw new Exception("An error occured while validating combination of data, please try again later or contact with site admin.");
                        // check user id
                        $userDetails = $this->user_model->checkUserEmail('', $userId);
                        if ($userDetails) throw new Exception("An error occured while validating combination of data, please try again later or contact with site admin.");
                        //IF both email and user id not find in data base, Insert input data in data base and set session
                        $inputData['email'] = $email;
                        $inputData['full_name'] = $fullName;
                        $inputData['fk_user_id'] = $userId;
                        $inputData['role'] = 'user';
                        $inputData['created_date'] = date('Y-m-d H:i:s');
                        $inputData['updated_date'] = date('Y-m-d H:i:s');
                        $insertId = $this->user_model->addDetails(TBL_TEST_USER, $inputData);
                        if ($insertId) {
                            $this->user_model->saveActivityLog($insertId, TEST_REGISTRATION, 'test registration', TBL_TEST_USER, '');
                            $userDetails = $this->user_model->getUserDetails($insertId);
                            //send email to admin
                            $to = ADMIN_EMAIL;
                            $subject = SITE_NAME . ' - New User Sign Up';
                            $from = ADMIN_EMAIL_NO_RPLY;
                            $mailContent = '<p style="padding-bottom:15px; margin:0; font-size: 22px; color: #a1a1a1; text-align: left; font-weight: 100; font-family: Helvetica, Arial, sans-serif;">Dear ' . SITE_NAME . '<p> 
                                 <p>A new user joins in ' . SITE_NAME . '.</p> 
                             <p><b>User Details-</b></b> 
                         <p>ID: <b>' . $userDetails->user_id . '</b></p> 
                         <p>Name: <b>' . $userDetails->full_name . '</b></p> 
                         <p>Email: <b>' . $userDetails->email . '</b></p> 
                                                 <p>Thanks,</p> 
                                                 <p>The ' . SITE_NAME . ' Team</p>';
                            $this->user_model->sendEmail($to, $from, $subject, $mailContent);
                            if ($userDetails) {
                                // Set login sesstion
                                $sessionData = array('testSesUserId' => $userDetails->user_id, 'userEmail' => $userDetails->email, 'userFullName' => $userDetails->full_name);
                                $this->session->set_userdata($sessionData);
                                $this->user_model->saveActivityLog($userDetails->fk_user_id, TEST_LOGIN, 'test login, test user id=' . $userDetails->user_id, TBL_TEST_USER, '');
                                redirect('dashboard');
                            } else throw new Exception("An error occured while fatching data , please try again later or contact with site admin.");
                        } else throw new Exception("An error occured while inserting data , please try again later or contact with site admin.");
                    }
                }
            }
            catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            die("No Access to user this URL");
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */