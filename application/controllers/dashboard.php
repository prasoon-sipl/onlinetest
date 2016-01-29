<?php
/*
* Version       : 1.0
* Filename      : dashboard.php
* Purpose       : This class is handle the user dashboard page
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    var $isLogout = false;
    var $isAjaxCall = false;
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        if (!$this->session->userdata('testSesUserId')) redirect('/');
        $this->load->library('template');
        $this->load->model('dashboard_model');
        if ($this->input->is_ajax_request()) $this->isAjaxCall = true;
    }
    /*
    *Methodname:  index
    *Purpose: This function open the dashboard page
    */
    public function index() {
        //Get the available test category
        $data['testCatagory'] = $this->dashboard_model->getTestCategory($this->session->userdata('testSesUserId'));
        $this->template->title = 'Dashboard - ' . SITE_NAME;
        $this->template->addJSLink(base_url(JS . 'jquery.dataTables.js'));
        $this->template->content = $this->load->view('dashboard/dashboard_view', $data, true);
        $this->template->renderTemplate();
    }
}
/* End of file home.php */
/* Location: ./application/controllers/dashboard.php */