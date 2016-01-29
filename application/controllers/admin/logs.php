<?php
/*
* Version       : 1.0
* Filename      : logs.php
* Purpose       : This class is will handle user logs activity.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Logs extends CI_Controller {
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        //Check admin session id is unset and redirct site index page
        if (!$this->session->userdata('adminId')) redirect('/');
        //Load model class
        $this->load->library('template');
        $this->load->model(ADMIN . 'logs_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display user logs information
    */
    public function index() {
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
        //Boot strap datepicker css
        $this->template->addCSSLink(base_url(CSS . 'datepicker.css'));
        //Jqgrid
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        //Bootstrap datepikcer js
        $this->template->addJSLink(base_url(JS . 'bootstrap-datepicker.js'));
        $this->template->title = 'Logs - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'logs/logs_view', '', true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname: logsGrid
    *Purpose: Display logs grid
    */
    public function logsGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->logs_model->logsGrid($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->logs_model->logsGrid($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
}
/* End of file logs.php */
/* Location: ./application/controllers/logs.php */