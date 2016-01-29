<?php
/*
* Version       : 1.0
* Filename      : configuration.php
* Purpose       : This class is will handle admin configuration functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Configuration extends CI_Controller {
    private $result = array('status' => false, 'message' => '', 'isLogout' => false, 'error' => false);
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
        $this->load->model(ADMIN . 'configuration_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display the index page of the configuration
    */
    public function index() {
        $this->category();
    }
    /*
    *Methodname:  index
    *Purpose: Display the index page of the configuration
    */
    public function category() {
        //Jqgrid
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
        //Jqgrid
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        //Bootstrap datepikcer js
        //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
        $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
        $data['questionsCategories'] = $this->configuration_model->getQuestionsParentCategory();
        $data['diffLevels'] = $this->configuration_model->getdiffLevels();
        $this->template->title = 'Tests - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'configuration/question_categories_view', $data, true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname:  getQuestionAns
    *Purpose: Display questions and answers list
    */
    public function getCategoryQuestionAns($categoryId = '') {
        if (empty($categoryId)) exit(0);
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE), "categoryId" => $categoryId);
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->configuration_model->getCategoryQuestionAns($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->configuration_model->getCategoryQuestionAns($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  removeQuestion
    *Purpose: Display the questions of category
    */
    public function removeCategQues() {
        try {
            if (!$this->input->is_ajax_request() || !$this->input->post()) throw new Exception('Invalid request!');
            $questionId = $this->input->post('questionId');
            $categoryId = $this->input->post('categoryId');
            if (!empty($questionId) && !empty($categoryId)) {
                if ($this->configuration_model->removeCategQues($questionId, $categoryId)) {
                    $this->result['status'] = true;
                    $this->result['message'] = 'Question has been removed successfully.';
                }
            } else throw new Exception('Invalid request!');
            echo json_encode($this->result);
        }
        catch(Exception $e) {
            $this->result['message'] = $e->getMessage();
            echo json_encode($this->result);
        }
    }
    /*
    *Methodname:  deleteQuesCategory
    *Purpose: Display the questions of category
    */
    public function deleteQuesCategory($categoryId = '') {
        if (empty($categoryId)) {
            $this->session->set_flashdata('errorMsg', 'Test id doesn\'t exists.');
            redirect(ADMIN . '/configuration/category');
        }
        if ($this->configuration_model->deleteQuesCategory($categoryId)) {
            $this->session->set_flashdata('successMsg', 'Test has been deleted successfully.');
            redirect(ADMIN . '/configuration/category');
        } else {
            $this->session->set_flashdata('errorMsg', 'Test can not be deleted.');
            redirect(ADMIN . '/configuration/category');
        }
    }
    /*
    *Methodname:  viewQuesCategory
    *Purpose: Display the questions of category
    */
    public function viewQuesCategory($categoryId = '') {
        if (empty($categoryId)) {
            $this->session->set_flashdata('errorMsg', 'Test id doesn\'t exists.');
            redirect(ADMIN . '/configuration/category');
        }
        try {
            //Jqgrid
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
            //Jqgrid
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
            $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
            //Bootstrap datepikcer js
            //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
            if (!$data['category'] = $this->configuration_model->getCategoryDetails($categoryId, true)) throw new Exception('Question category doesn\'t exists!');
            if (empty($data['category']->parent_category_title)) throw new Exception('There is no question associated with this test.');
            $data['diffLevels'] = $this->configuration_model->getdiffLevels();
            $this->template->title = 'Test - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'configuration/category_questions_view', $data, true);
            $this->template->renderAdminTemplate();
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    /*
    *Methodname:  index
    *Purpose: Display the index page of the configuration
    */
    public function questionsAssign($categoryId = '') {
        try {
            if (empty($categoryId)) throw new Exception('Wrong Input!');
            //Jqgrid
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
            //Jqgrid
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
            // add jquery UI CSS and JS
            $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
            $this->template->addJSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.js'));
            //Bootstrap datepikcer js
            //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
            if (!$data['category'] = $this->configuration_model->getCategoryDetails($categoryId)) throw new Exception('Question category doesn\'t exists!');
            $data['subcategory'] = $this->configuration_model->getSubCategories($categoryId);
            $this->template->title = 'Test - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'configuration/assign_questions_view.php', $data, true);
            $this->template->renderAdminTemplate();
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    /*
    *Methodname:  categoriesGrid
    *Purpose: Display catagories grid
    */
    public function categoriesGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->configuration_model->getCategories($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->configuration_model->getCategories($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  filterByCategory
    *Purpose: get results based on category id
    */
    public function filterQuesByCategory() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE), "categoryId" => $this->input->post("categoryId", TRUE), "mainCategoryId" => $this->input->post("mainCategoryId", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->configuration_model->filterQuesByCategory($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->configuration_model->filterQuesByCategory($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  addRemoveQuestions
    *Purpose: add/remove category from questions
    */
    public function addRemoveQuestions() {
        //print_r($this->input->post());
        try {
            if (!$this->input->is_ajax_request() || !$this->input->post()) throw new Exception('Invalid request!');
            $action = $this->input->post('action');
            $questionsId = $this->input->post('questionsId');
            $categoryId = $this->input->post('categoryId');
            if ($action != 'checked' && $action != 'unchecked') throw new Exception('Action is invalid');
            if (empty($questionsId)) throw new Exception('Questions id is invalid');
            if (empty($categoryId)) throw new Exception('category id is invalid');
            if ($this->configuration_model->addRemoveQuestions($action, $questionsId, $categoryId)) {
                $this->result['status'] = true;
                if ($action == 'checked') $this->result['message'] = 'Question has been saved successfully.';
                else $this->result['message'] = 'Question has been removed successfully.';
                echo json_encode($this->result);
            } else throw new Exception('We couldn\'t process your request, try again!');
        }
        catch(Exception $e) {
            $this->result['message'] = $e->getMessage();
            echo json_encode($this->result);
        }
    }
    /*
    *Methodname:  saveQuesSubcategories
    *Purpose: add/remove category from questions
    */
    public function saveQuesSubcategories($mainCategory = '') {
        //print_r($_POST);
        try {
            if (!$this->input->is_ajax_request() || !$this->input->post() || empty($mainCategory)) throw new Exception('Invalid request!');
            $subCategories = $this->input->post('sub_categories');
            $subCategoriesAll = array();
            if (is_array($subCategories)) {
                $valid = false;
                foreach($subCategories as $category) {
                    if ($id = $this->input->post('sub_categories_' . $category)) {
                        $valid = true;
                        if ($id == 'all') $subCategoriesAll['sub_categories_' . $category] = $id;
                    }
                }
                if (!$valid) throw new Exception('Please select at least one category.');
                if (count($subCategoriesAll)) {
                    if ($this->configuration_model->saveQuesSubcategories($mainCategory, $subCategoriesAll)) {
                        $this->result['status'] = true;
                        $this->result['message'] = 'Changes has been saved successfully.';
                    } else throw new Exception('An error was encountered please try again later.');
                } else {
                    $this->result['status'] = true;
                    $this->result['message'] = 'Changes has been saved saved successfully.';
                }
            } else throw new Exception('Invalid request!');
            echo json_encode($this->result);
        }
        catch(Exception $e) {
            $this->result['message'] = $e->getMessage();
            echo json_encode($this->result);
        }
    }
    /*
    *Methodname:  diffLevel
    *Purpose: Display the index page of the configuration
    */
    public function diffLevel() {
        //Jqgrid
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
        $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
        //Jqgrid
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
        $this->template->addJSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.js'));
        $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
        //Bootstrap datepikcer js
        //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
        $data['questionsCategories'] = $this->configuration_model->getQuestionsParentCategory();
        $this->template->title = 'Levels - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'configuration/diff_level_view', $data, true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname: sortDiffLevels
    *Purpose: sort the level
    */
    public function sortDiffLevels() {
        $perPage = $this->input->post('perPage');
        $pageNo = $this->input->post('pageNo');
        $diffIds = $this->input->post('diffIds');
        $diffIds = explode(',', $diffIds);
        $orderTo = $perPage * $pageNo;
        $orderFrom = $orderTo - $perPage;
        for ($i = 0;$i < count($diffIds);$i++) {
            $sort['preference'] = $orderFrom + 1;
            $result = $this->configuration_model->updateDifficultyDetail(TBL_DIFFICULTY_LEVELS, 'difficulty_levels_id', $diffIds[$i], $sort);
            $orderFrom++;
        }
        echo 1;
    }
    public function editQuesCategory($categoryId = '') {
        try {
            if (empty($categoryId)) throw new Exception('Wrong inputs!');
            if ($categoryId == DEFAULT_CATEGORY || $categoryId == PARENT_CATEGORY) throw new Exception('Question category doesn\'t exists!');
            //Jqgrid
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
            //Jqgrid
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
            //Bootstrap datepikcer js
            //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
            $data['questionsCategories'] = $this->configuration_model->getQuestionsParentCategory();
            $data['diffLevels'] = $this->configuration_model->getdiffLevels();
            $data['quesCategoryId'] = $categoryId;
            if (!$data['formData'] = $this->configuration_model->getQuestionCategoryData($categoryId)) throw new Exception('Question category doesn\'t exists!');
            $this->template->title = 'Test - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'configuration/edit_quescategory_view', $data, true);
            $this->template->renderAdminTemplate();
        }
        catch(Exception $e) {
            echo $e->getMessage();
            $this->session->set_flashdata('errorMsg', $e->getMessage());
            redirect(ADMIN . 'configuration');
        }
    }
    /*
    *Methodname:  diffLevelGrid
    *Purpose: Display diff levels grid
    */
    public function diffLevelGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->configuration_model->diffLevels($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->configuration_model->diffLevels($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  checkCategoryName
    *Purpose: check category name before create new
    */
    public function checkCategoryName() {
        $categoryName = $this->input->post('category_title', TRUE);
        $questionCategoryId = $this->input->post('question_category_id', TRUE);
        if ($this->configuration_model->checkCategoryName($categoryName, $questionCategoryId)) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  checkDiffLevels
    
    *Purpose: check category name before create new
    */
    public function checkDiffLevels() {
        $diffLevelTitle = $this->input->post('difficulty_levels_title', TRUE);
        if ($this->configuration_model->checkDiffLevels($diffLevelTitle, 'add', '')) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  addDiffLevel
    *Purpose: manage difficulty level
    */
    public function addDiffLevel() {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            $this->result['status'] = false;
            $this->result['message'] = 'Invalid request.';
            echo json_encode($this->result);
            exit(0);
        }
        $this->load->library('form_validation');
        $validattionField = array(array('field' => 'difficulty_levels_title', 'label' => 'Required|numeric', 'rules' => 'required|trim|is_unique[' . TBL_DIFFICULTY_LEVELS . '.difficulty_levels_title]'));
        $this->form_validation->set_rules($validattionField);
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('is_unique', 'Difficulty level title already exists.');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $error = array();
            $error['difficulty_levels_title'] = form_error('difficulty_levels_title');
            $this->result['error']['formerror'] = $error;
            $this->result['message'] = 'Incorrect form input.';
        } else {
            $postArray['difficulty_levels_title'] = $this->input->post('difficulty_levels_title', true);
            $postArray['created_date'] = date('Y-m-d H:i:s');
            $postArray['updated_date'] = date('Y-m-d H:i:s');
            if ($this->configuration_model->addDiffLevel($postArray)) {
                $this->result['message'] = 'Difficulty level has been saved successfully.';
                $this->result['status'] = true;
            } else $this->result['message'] = 'An error was encountered please try again later.';
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  editDiffLevels
    *Purpose: Manage edit functionlty through grid of category
    */
    public function editDiffLevels() {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            throw new Exception('Invalid request!');
        }
        try {
            //print_r($this->input->post());
            $this->load->library('form_validation');
            $validattionField = array(array('field' => 'is_active', 'label' => 'Is Active status is required', 'rules' => 'required|trim|numeric'), array('field' => 'id', 'label' => 'Update id is missing', 'rules' => 'required|trim'));
            $this->form_validation->set_rules($validattionField);
            if (!$this->configuration_model->checkExistDiffLevel($this->input->post('difficulty_levels_title'), $this->input->post('id'))) $this->form_validation->set_rules('difficulty_levels_title', 'Difficulty level title is required.', 'required|trim|is_unique[' . TBL_DIFFICULTY_LEVELS . '.difficulty_levels_title]');
            $this->form_validation->set_message('required', '%s');
            $this->form_validation->set_message('is_unique', 'Difficulty level title already exists.');
            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == FALSE) {
                $error = array('difficulty_levels_title' => form_error('difficulty_levels_title'), 'is_active' => form_error('is_active'), 'id' => form_error('id'));
                $this->result['error']['formerror'] = $error;
                throw new Exception('Incorrect form input.');
            } else {
                $formData['difficulty_levels_title'] = $this->input->post('difficulty_levels_title', true);
                $formData['is_active'] = $this->input->post('is_active', true);
                $formData['updated_date'] = date('Y-m-d H:i:s');
                $diffId = $this->input->post('id');
                if ($this->configuration_model->editDiffLevels($formData, $diffId)) {
                    $this->result['message'] = 'Difficulty level has been saved successfully.';
                    $this->result['status'] = true;
                } else $this->result['message'] = 'Difficulty level can not be saved.';
            }
        }
        catch(Exception $e) {
            $this->result['message'] = $e->getMessage();
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  addEditCategory
    *Purpose: Manage add/edit functionlty through grid of category
    */
    public function addEditCategory($quesCategoryId = '') {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            $this->result['status'] = false;
            $this->result['message'] = 'Invalid request!';
            echo json_encode($this->result);
            exit(0);
        }
        $this->load->library('form_validation');
        $validattionField = array(array('field' => 'test_duration', 'label' => 'Required', 'rules' => 'trim|required'), array('field' => 'no_of_questions', 'label' => 'Required', 'rules' => 'trim|required|callback_checkQuestion[diff_levels_questions]'), array('field' => 'total_marks', 'label' => 'Required', 'rules' => 'trim|required|callback_checkTotalMarks[diff_levels_questions]'));
        $this->form_validation->set_rules($validattionField);
        // checking category
        if (empty($quesCategoryId)) {
            $this->form_validation->set_rules('category_title', 'Category title is required', 'required|trim|is_unique[' . TBL_QUESTION_CATEGORY . '.category_title]');
        } else {
            if (!$this->configuration_model->checkCategoryName($this->input->post('category_title'), $quesCategoryId)) {
                $this->form_validation->set_rules('category_title', 'Category title is required.', 'required|trim');
            } else {
                $this->form_validation->set_rules('category_title', 'Category title is required.', 'required|trim|is_unique[' . TBL_QUESTION_CATEGORY . '.category_title]');
            }
        }
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('is_unique', 'Category already exists!');
        $this->form_validation->set_message('greater_than', '%s shoud be greater than or equal to %s.');
        $this->form_validation->set_error_delimiters('', '');
        $this->result['status'] = false;
        if ($this->form_validation->run() == FALSE) {
            $error = array('category_title' => form_error('category_title'), 'test_duration' => form_error('test_duration'), 'no_of_questions' => form_error('no_of_questions'), 'total_marks' => form_error('total_marks'));
            $this->result['error']['formerror'] = $error;
            $this->result['message'] = 'Incorrect form input.';
        } else {
            $postArray['updated_date'] = date('Y-m-d H:i:s');
            $postArray['created_date'] = date('Y-m-d H:i:s');
            $postArray['category_title'] = $this->input->post('category_title');
            $postArray['is_active'] = $this->input->post('is_active') ? 1 : 0;
            $diffLevel = $this->input->post('diff_levels_questions');
            $preference = $this->input->post('preference');
            $difficultyLevels = array();
            foreach($diffLevel as $key => $data) {
                if (!empty($data)) {
                    $difficultyLevels[$preference[$key]] = $data;
                }
            }
            $postArray['difficulty_levels'] = json_encode($difficultyLevels);
            $postArray['parent_category_id'] = PARENT_CATEGORY;
            $postArray['test_duration'] = $this->input->post('test_duration');
            $postArray['no_of_questions'] = $this->input->post('no_of_questions');
            $postArray['total_marks'] = $this->input->post('total_marks');
            if (empty($quesCategoryId)) {
                if ($categoryId = $this->configuration_model->addEditCategory($postArray)) {
                    $this->result['status'] = true;
                    $this->result['categoryId'] = $categoryId;
                    $this->result['message'] = 'Question category has been stored successfully.';
                } else $this->result['message'] = 'Some problem is occured!';
            } else {
                if ($categoryId = $this->configuration_model->addEditCategory($postArray, $quesCategoryId)) {
                    $this->result['status'] = true;
                    $this->result['categoryId'] = $categoryId;
                    $this->result['message'] = 'Test has been updated successfully.';
                } else $this->result['message'] = 'An error was encountered please try again later.';
            }
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  checkQuestion
    *Purpose: check validation of nymber if questions
    */
    public function checkQuestion($value, $diffLevel) {
        $sum = 0;
        $diffLevel = $this->input->post($diffLevel);
        $this->form_validation->set_message('checkQuestion', 'Questions sum doesn\'t match with difficulty level.');
        if (!is_array($diffLevel)) return false;
        foreach($diffLevel as $data) {
            if (!empty($data)) $sum+= $data;
        }
        if ($sum == $value) return true;
        return false;
    }
    /*
    *Methodname: checkTotalMarks
    *Purpose: check validation of total marks
    */
    public function checkTotalMarks($value, $diffLevel, $preference = 'preference') {
        $totalMarks = 0;
        $diffLevel = $this->input->post($diffLevel);
        $preference = $this->input->post($preference);
        $this->form_validation->set_message('checkTotalMarks', 'Total Marks calculation is wrong.');
        if (!is_array($diffLevel) && !is_array($preference)) return false;
        foreach($diffLevel as $key => $data) {
            if (!empty($data)) {
                $totalMarks+= $data * $preference[$key];
            }
        }
        if ($totalMarks == $value) return true;
        return false;
    }
}
/* End of file configuration.php */
/* Location: ./application/controllers/admin/configuration.php */