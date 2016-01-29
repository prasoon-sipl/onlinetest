<?php
/*
* Version       : 1.0
* Filename      : settings.php
* Purpose       : This class is will handle admin settings functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        if (!$this->session->userdata('adminId')) {
            if ($this->input->is_ajax_request()) $this->result['isLogout'] = true;
            else redirect('/');
        }
        $this->load->library('template');
        $this->load->model(ADMIN . 'settings_model');
    }
    /*
    *Methodname:  index
    *Purpose: open setting page
    */
    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('email', 'Parent Dream Id', 'trim|email|max_length[45]|callback__checkEmail');
        if ($this->form_validation->run() == FALSE) {
            if ($this->input->post('password') || $this->input->post('old_password')) {
                $this->form_validation->set_rules('password', 'Password', 'trim|max_length[16]');
                $this->form_validation->set_rules('old_password', 'Old Password', 'trim|max_length[16]|callback__checkPassword');
            }
            $this->form_validation->set_rules('is_active', 'Is Active', 'required|trim|max_length[1]|numeric');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $data['settings'] = $this->settings_model->getAdminDetails();
            $this->template->title = 'Setting - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'settings/settings_view', $data, true);
            $this->template->renderAdminTemplate();
        } else {
            //form fields
            $fields['full_name'] = $this->input->post('full_name', TRUE);
            $fields['email'] = $this->input->post('email', TRUE);
            if ($this->input->post('password')) {
                //change password
                $this->load->library('phpass'); //load hash pass lib
                $fields['password'] = $this->phpass->hash($this->input->post('password'));
            }
            $fields['is_active'] = $this->input->post('is_active', TRUE);
            $this->settings_model->updateDetail(TBL_TEST_USER, 'user_id', $this->session->userdata('adminId'), $fields);
            $this->session->set_flashdata('successMsg', 'Information has been updated successfully.');
            redirect(ADMIN . 'settings');
        }
    }
    /*
    *Methodname:  checkEmail
    *Purpose: To check email
    */
    public function checkEmail() {
        $email = $this->input->post('email', TRUE);
        if ($this->settings_model->checkEmail($email)) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  _checkEmail
    *Purpose: To check email
    */
    function _checkEmail() {
        $email = $this->input->post('email', TRUE);
        $this->form_validation->set_message('_checkEmail', 'Email already exists. Please choose another email.');
        if ($this->settings_model->checkEmail($email)) return FALSE;
        else return TRUE;
    }
    /*
    *Methodname:  _checkPassword
    *Purpose: To check old password
    */
    function _checkPassword() {
        $password = $this->input->post('old_password', TRUE);
        $this->form_validation->set_message('_checkPassword', 'Current password does not match, please enter correct password.');
        $settings = $this->settings_model->getAdminDetails();
        //check password
        $this->load->library('phpass'); //load hash pass lib
        if ($this->phpass->check($password, $settings->password)) {
            if ($this->input->post('password', TRUE) == $this->input->post('old_password', TRUE)) {
                $this->form_validation->set_message('_checkPassword', 'Current password and new passsword shouldn\'t be equal.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else return FALSE;
    }
}
/* End of file settings.php */
/* Location: ./application/controllers/admin/settings.php */