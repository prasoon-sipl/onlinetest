<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Version       : 1.0
* Filename      : termsandconditions.php
* Purpose       : This class will handle terms and conditions of product.
*/
class Termsandconditions extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->library('template');
    }
    /*
    *Methodname:  index
    *Purpose: Display terms & conditions
    */
    public function index() {
        $this->template->title = 'Terms & conditions - ' . SITE_NAME;
        $this->template->content = $this->load->view('termsandconditions/termsandconditions_view', "", true);
        $this->template->renderTemplate();
    }
}
/* End of file inmaking.php */
/* Location: ./application/controllers/termsandconditions.php */