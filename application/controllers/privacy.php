<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : privacy.php
* Purpose       : This class will handle privacy  and policy of product.
*/
class Privacy extends CI_Controller {
    /*
    * Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->library('template');
    }
    /*
    *Methodname:  index
    *Purpose: Display privacy & policy
    */
    public function index() {
        $this->template->title = 'Privacy & Policy - ' . SITE_NAME;
        $this->template->content = $this->load->view('privacy/privacy_view', "", true);
        $this->template->renderTemplate();
    }
}
/* End of file inmaking.php */
/* Location: ./application/controllers/privacy.php */