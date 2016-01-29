<?php
/*
* Version       : 1.0
* Filename      : home.php
* Purpose       : This class is handle the home page
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->helper('cookie');
        $this->load->library('encrypt');
        $this->load->library('template');
        $this->load->model('home_model');
    }
    /*
    *Methodname:  index
    *Purpose: This function open the home page
    */
    public function index() {
        if ($this->session->userdata('testSesUserId')) {
            redirect('dashboard');
        }
        $this->template->title = 'Home - ' . SITE_NAME;
        $this->template->content = $this->load->view('home/home_view', '', true);
        $this->template->renderTemplate();
    }
    /*
    *Methodname:  error404
    *Purpose: This function open the 404 error page
    */
    public function error404() {
        $this->template->title = 'Page not found - ' . SITE_NAME;
        $this->template->content = $this->load->view('not_found_view', '', true);
        $this->template->renderTemplate();
    }
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */