<?php
/*
* Version       : 1.0
* Filename      : dashboard.php
* Purpose       : This class is will handle admin dashboard functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        //Check admin session id is unset and redirect site index page
        if (!$this->session->userdata('adminId')) redirect('/');
        //Load model class
        $this->load->library('template');
        $this->load->model(ADMIN . 'dashboard_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display admin dashboard page
    */
    public function index() {
        $this->template->title = 'Admin Dashboard';
        $data['testCatagory'] = $this->dashboard_model->getTestCategory();
        $this->template->title = 'Dashboard - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'dashboard/dashboard_view', $data, true);
        $this->template->renderAdminTemplate();
    }
}
/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */