<?php
/*
* Version       : 1.0
* Filename      : usertest.php
* Purpose       : This class is will handle user's test.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usertest extends CI_Controller {
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
        $this->load->model(ADMIN . 'usertest_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display user information and give option to add user
    */
    public function index() {
        //Jqgrid
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
        //Jqgrid
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.js'));
        $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
        //Bootstrap datepikcer js
        $this->template->title = 'User Test - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'usertest/usertest_view', '', true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname: usertestGrid
    *Purpose: Display user test grid
    */
    public function usertestGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->usertest_model->getUsertest($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->usertest_model->getUsertest($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  deleteUserTest
    *Purpose: delete user test
    */
    public function deleteUserTest($userTestId = '') {
        if (empty($userTestId)) {
            $this->session->set_flashdata('errorMsg', 'User test doesn\'t exists.');
            redirect(ADMIN . '/usertest');
        }
        $postArray['is_deleted'] = 1;
        if ($this->usertest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $userTestId, $postArray)) {
            $this->session->set_flashdata('successMsg', 'User test has been deleted successfully.');
            redirect(ADMIN . 'usertest');
        } else {
            $this->session->set_flashdata('errorMsg', 'User test can not be deleted.');
            redirect(ADMIN . 'usertest');
        }
    }
}
/* End of file user.php */
/* Location: ./application/controllers/admin/usertest.php */