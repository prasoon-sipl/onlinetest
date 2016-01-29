<?php
/*
* Version       : 1.0
* Filename      : report.php
* Purpose       : This class is handle the user test page
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Report extends CI_Controller {
    var $isLogout = false;
    var $isAjaxCall = false;
    var $categoryTitle;
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->destroyTestSession();
        $this->load->library('template');
        $this->load->model('report_model');
        if ($this->input->is_ajax_request()) $this->isAjaxCall = true;
    }
    /*
    *Methodname:  index
    *Purpose: This function open for showing all reports of user
    */
    public function index() {
        if (!$this->session->userdata('testSesUserId')) redirect('/');
        $this->template->addJSLink(base_url(JS . 'jquery.dataTables.js'));
        $data['reports'] = $this->report_model->getTestReports($this->session->userdata('testSesUserId'));
        $this->template->title = 'Skill Test - ' . SITE_NAME;
        $this->template->content = $this->load->view('report/report_list_view', $data, true);
        $this->template->renderTemplate();
    }
    /*
    *Methodname:  status
    *Purpose: This function is used to check test status and show test information
    */
    public function status($testId = '') {
        if (!$this->session->userdata('testSesUserId')) redirect('/');
        if (empty($testId)) {
            $this->session->set_flashdata('errorMsg', 'Invalid Request.');
            redirect('report');
        }
        if (!$data['report'] = $this->report_model->getTestReports($this->session->userdata('testSesUserId'), $testId)) {
            $this->session->set_flashdata('errorMsg', 'Test result is not available.');
            redirect('report');
        }
        $this->template->title = 'Skill Test - ' . SITE_NAME;
        $this->template->content = $this->load->view('report/report_status_view', $data, true);
        $this->template->renderTemplate();
    }
    /*
    *Methodname:  destroyTestSession
    *Purpose: this function is used distroy test session
    */
    public function destroyTestSession() {
        $this->session->set_userdata('userTestId', false);
        $this->session->set_userdata('testName', false);
        $this->session->set_userdata('difficultyLevels', false);
        $this->session->set_userdata('testDuration', false);
        $this->session->set_userdata('currentLevel', false);
        $this->session->set_userdata('currentLevelQuestions', false);
        $this->session->set_userdata('totalQuestions', false);
        $this->session->set_userdata('noOFQuestionsGiven', false);
        $this->session->set_userdata('lastQuestionID', false);
        $this->session->set_userdata('testStatus', false);
        $this->session->set_userdata('levelCheckFlag', false);
    }
}
/* End of file report.php */
/* Location: ./application/controllers/report.php */