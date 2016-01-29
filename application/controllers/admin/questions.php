<?php
/*
* Version       : 1.0
* Filename      : Questions.php
* Purpose       : This class is will handle admin questions functionality.
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Questions extends CI_Controller {
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
        $this->load->model(ADMIN . 'questions_model');
    }
    /*
    *Methodname:  index
    *Purpose: Display the index page of the questions
    */
    public function index() {
        $this->_setQuestionRequiredField(1);
        if ($this->form_validation->run() == FALSE) {
            $data = array();
            if ($this->input->post()) {
                $data['selCat'] = $this->input->post('category_id');
                $data['selAns'] = $this->input->post('answer');
                if ($this->input->post('answer_type') == 2) $data['correctAns'] = $this->input->post('correctAns');
                else $data['correctAns'][] = $this->input->post('correctAns');
            }
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
            //Jqgrid
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
            $this->template->addCSSLink(base_url(PLUGIN . 'jqueryui/jquery-ui.min.css'));
            //Bootstrap datepikcer js
            //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
            $data['questionsCategories'] = $this->questions_model->getQuestionsSubCategory();
            $data['diffLevels'] = $this->questions_model->getdiffLevels();
            $this->template->title = 'Questions - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'questions/questions_view', $data, true);
            $this->template->renderAdminTemplate();
        } else {
            // Default category id
            $subCategoriesArr[] = DEFAULT_CATEGORY;
            $subCategories = implode(',', $this->input->post('category_id'));
            $tempSubCategoriesArr = explode(',', $subCategories);
            foreach($tempSubCategoriesArr as $cat) $subCategoriesArr[] = $cat;
            $subCategories = implode(',', $subCategoriesArr);
            // question table data
            $questioData['sub_categories'] = $subCategories;
            $questioData['question_title'] = $this->input->post('question_title', true);
            $questioData['question_description'] = $this->input->post('question_description');
            if (is_array($this->input->post('correctAns'))) $questioData['correct_answers'] = implode(',', $this->input->post('correctAns'));
            else $questioData['correct_answers'] = $this->input->post('correctAns');
            $questioData['fk_difficulty_levels_id'] = $this->input->post('difficulty_levels_id');
            $questioData['created_date'] = date('Y-m-d H:i:s');
            $questioData['updated_date'] = date('Y-m-d H:i:s');
            $questioData['answer_type'] = $this->input->post('answer_type');
            $questioData['no_of_options'] = $this->input->post('no_of_options');
            $answer = $this->input->post('answer');
            $correctAns = $this->input->post('correctAns');
            if ($this->questions_model->saveQuestionAnswer($questioData, $answer, $correctAns)) {
                $this->session->set_flashdata('successMsg', 'Question has been added successfully!.');
                redirect(ADMIN . 'questions');
            } else {
                $this->session->set_flashdata('errorMsg', $this->questions_model->errorMessage);
                redirect(ADMIN . 'questions');
            }
        }
    }
    /*
    *Methodname:  questionsSubgrid
    *Purpose: Display the index page of the questions
    */
    public function questionsSubgrid() {
        $this->_setQuestionRequiredField(1);
        if ($this->form_validation->run() == FALSE) {
            $data = array();
            if ($this->input->post()) {
                $data['selCat'] = $this->input->post('category_id');
                $data['selAns'] = $this->input->post('answer');
                if ($this->input->post('answer_type') == 2) $data['correctAns'] = $this->input->post('correctAns');
                else $data['correctAns'][] = $this->input->post('correctAns');
            }
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
            $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
            //Jqgrid
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
            $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
            //Bootstrap datepikcer js
            //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
            $data['questionsCategories'] = $this->questions_model->getQuestionsSubCategory();
            $data['diffLevels'] = $this->questions_model->getdiffLevels();
            $this->template->title = 'Questions - ' . SITE_NAME;
            $this->template->content = $this->load->view(ADMIN . 'questions/questions_view_subgrid', $data, true);
            $this->template->renderAdminTemplate();
        } else {
            // question table data
            $questioData['sub_categories'] = implode(',', $this->input->post('category_id'));
            $questioData['question_title'] = $this->input->post('question_title', true);
            $questioData['question_description'] = $this->input->post('question_description');
            if (is_array($this->input->post('correctAns'))) $questioData['correct_answers'] = implode(',', $this->input->post('correctAns'));
            else $questioData['correct_answers'] = $this->input->post('correctAns');
            $questioData['fk_difficulty_levels_id'] = $this->input->post('difficulty_levels_id');
            $questioData['created_date'] = date('Y-m-d H:i:s');
            $questioData['updated_date'] = date('Y-m-d H:i:s');
            $questioData['answer_type'] = $this->input->post('answer_type');
            $questioData['no_of_options'] = $this->input->post('no_of_options');
            $answer = $this->input->post('answer');
            $correctAns = $this->input->post('correctAns');
            if ($this->questions_model->saveQuestionAnswer($questioData, $answer, $correctAns)) {
                $this->session->set_flashdata('successMsg', 'Question has been added successfully!.');
                redirect(ADMIN . 'questions');
            } else {
                $this->session->set_flashdata('errorMsg', $this->questions_model->errorMessage);
                redirect(ADMIN . 'questions');
            }
        }
    }
    /*
    *Methodname:  index
    *Purpose: Display the index page of the questions
    */
    public function editQuestion($questionId = '') {
        try {
            if (empty($questionId)) throw new Exception('Wrong inputs!');
            $this->_setQuestionRequiredField(1);
            if ($this->form_validation->run() == FALSE) {
                $data = array();
                if ($this->input->post()) {
                    $data['selCat'] = $this->input->post('category_id');
                    $data['selAns'] = $this->input->post('answer');
                    if ($this->input->post('answer_type') == 2) $data['correctAns'] = $this->input->post('correctAns');
                    else $data['correctAns'][] = $this->input->post('correctAns');
                }
                $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGrid.css'));
                $this->template->addCSSLink(base_url(PLUGIN . 'Jqgrid/css/jqGridCustom.css'));
                //Jqgrid
                $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/grid.locale-en.js'));
                $this->template->addJSLink(base_url(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js'));
                //Bootstrap datepikcer js
                //$this->template->addJSLink(JS.'bootstrap-datepicker.js');
                // get question form data
                if (!$data['questionData'] = $this->questions_model->getQuestionData($questionId)) throw new Exception('Question data doesn\'t exists.');
                // get drop down data
                $data['questionsCategories'] = $this->questions_model->getQuestionsSubCategory();
                $data['diffLevels'] = $this->questions_model->getdiffLevels();
                $this->template->title = 'Questions - ' . SITE_NAME;
                $this->template->content = $this->load->view(ADMIN . 'questions/edit_question_view', $data, true);
                $this->template->renderAdminTemplate();
            } else {
                // Default category id
                $subCategoriesArr[] = DEFAULT_CATEGORY;
                $subCategories = implode(',', $this->input->post('category_id'));
                $tempSubCategoriesArr = explode(',', $subCategories);
                foreach($tempSubCategoriesArr as $cat) $subCategoriesArr[] = $cat;
                $subCategories = implode(',', $subCategoriesArr);
                $questioData['sub_categories'] = $subCategories;
                $questioData['question_title'] = $this->input->post('question_title', true);
                $questioData['question_description'] = $this->input->post('question_description');
                if (is_array($this->input->post('correctAns'))) $questioData['correct_answers'] = implode(',', $this->input->post('correctAns'));
                else $questioData['correct_answers'] = $this->input->post('correctAns');
                $questioData['fk_difficulty_levels_id'] = $this->input->post('difficulty_levels_id');
                $questioData['created_date'] = date('Y-m-d H:i:s');
                $questioData['updated_date'] = date('Y-m-d H:i:s');
                $questioData['answer_type'] = $this->input->post('answer_type');
                $questioData['no_of_options'] = $this->input->post('no_of_options');
                $questioData['is_active'] = $this->input->post('is_active') ? 1 : 0;
                $answer = $this->input->post('answer');
                $correctAns = $this->input->post('correctAns');
                if ($this->questions_model->updateQuestionAnswer($questionId, $questioData, $answer, $correctAns)) {
                    $this->session->set_flashdata('successMsg', 'Question has been updated successfully!.');
                    redirect(ADMIN . 'questions/editQuestion/' . $questionId);
                } else {
                    $this->session->set_flashdata('errorMsg', $this->questions_model->errorMessage);
                    redirect(ADMIN . 'questions/editQuestion/' . $questionId);
                }
            }
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    /*
    *Methodname: _setQuestionRequiredField
    *Purpose: To set dream validation
    */
    private function _setQuestionRequiredField($step) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category_id[]', 'Select atleast one category.', 'required');
        $this->form_validation->set_rules('answer_type', 'Choose answer type', 'trim|required');
        $this->form_validation->set_rules('no_of_options', 'No Of Answers', 'required|trim');
        $this->form_validation->set_rules('difficulty_levels_id', 'Select difficulty level', 'required|trim|numeric');
        $this->form_validation->set_rules('question_title', 'Question Subject', 'trim|max_length[100]');
        $this->form_validation->set_rules('question_description', 'Enter question\'s description', 'trim|min_length[2]|max_length[5000]|callback_checkQuestionDesc');
        if ($this->input->post('answer_type') == 1) $this->form_validation->set_rules('correctAns', 'Choose any answer.', 'trim|required');
        if ($this->input->post('answer_type') == 2) $this->form_validation->set_rules('correctAns[]', 'Select atleast one correct answer.', 'trim|required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'trim|numeric');
        $this->form_validation->set_rules('answer[]', 'Enter all answer\'s title.', 'required|trim|min_length[2]|max_length[256]');
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('greater_than', '%s shoud be greater than or equal to %s.');
        $this->form_validation->set_message('max_length', '%s should not be greater than %s characters.');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
    }
    /*
    *Methodname: checkQuestionDesc
    *Purpose: check question validation
    */
    public function checkQuestionDesc($qDesc) {
        $qDesc = strip_tags($qDesc);
        if (empty($qDesc)) {
            $this->form_validation->set_message('checkQuestionDesc', 'Question description must have to be entered.');
            return FALSE;
        } else return true;
    }
    /*
    *Methodname:  getQuestionAns
    *Purpose: Display questions and answers list
    */
    public function getQuestionAns() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->questions_model->getQuestionAns($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->questions_model->getQuestionAns($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  getQuestions
    *Purpose: return in json questions
    */
    public function getQuestions($categoryId = '') {
        $reqParam = array("categoryId" => $categoryId, "sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->questions_model->getQuestions($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->questions_model->getQuestions($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  getAnswers
    *Purpose: return answer based on question
    */
    function getAnswers() {
        $result['rows'] = $this->questions_model->getAnswers($this->input->post('id'));
        echo json_encode($result);
    }
    /*
    *Methodname:  checkCategoryName
    *Purpose: check category name before create new
    */
    public function checkCategoryName() {
        $categoryName = $this->input->post('category_title', TRUE);
        if ($this->questions_model->checkCategoryName($categoryName, 'add', '')) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  addEditCategory
    *Purpose: Manage add/edit functionlty through grid of category
    */
    public function addEditCategory() {
        if (!$this->input->is_ajax_request() || !$this->input->post()) {
            $this->result['status'] = false;
            $this->result['message'] = 'Invalid request!';
            echo json_encode($this->result);
            exit(0);
        }
        $this->load->library('form_validation');
        if ($this->input->post('is_parent')) {
            $validattionField = array(array('field' => 'category_title', 'label' => 'Required|numeric', 'rules' => 'required|trim|is_unique[' . TBL_QUESTION_CATEGORY . '.category_title]'));
        } else {
            $validattionField = array(array('field' => 'category_title', 'label' => 'Required', 'rules' => 'required|trim|is_unique[' . TBL_QUESTION_CATEGORY . '.category_title]'), array('field' => 'parent_category_id', 'label' => 'Required', 'rules' => 'trim|required'), array('field' => 'test_duration', 'label' => 'Required', 'rules' => 'trim|required'), array('field' => 'no_of_questions', 'label' => 'Required', 'rules' => 'trim|required'), array('field' => 'total_marks', 'label' => 'Required', 'rules' => 'trim|required'));
        }
        $this->form_validation->set_rules($validattionField);
        $this->form_validation->set_message('required', '%s');
        $this->form_validation->set_message('is_unique', 'Category already exists!');
        $this->form_validation->set_error_delimiters('', '');
        $this->result['status'] = false;
        if ($this->form_validation->run() == FALSE) {
            $error = array();
            if ($this->input->post('is_parent')) {
                $error['category_title'] = form_error('category_title');
            } else {
                $error = array('category_title' => form_error('category_title'), 'parent_category_id' => form_error('parent_category_id'), 'test_duration' => form_error('test_duration'), 'no_of_questions' => form_error('no_of_questions'), 'total_marks' => form_error('total_marks'));
            }
            $this->result['error']['formerror'] = $error;
            $this->result['message'] = 'Incorrect form input.';
        } else {
            $postArray['updated_date'] = date('Y-m-d H:i:s');
            if ($this->input->post('oper') == 'edit') {
                /*if($this->questions_model->editCategory())
                echo json_encode(true);
                else
                echo json_encode(false);*/
            } else {
                $postArray['created_date'] = date('Y-m-d H:i:s');
                $postArray['category_title'] = $this->input->post('category_title');
                if (!$this->input->post('is_parent')) {
                    $postArray['parent_category_id'] = $this->input->post('parent_category_id');
                    $postArray['test_duration'] = $this->input->post('test_duration');
                    $postArray['no_of_questions'] = $this->input->post('no_of_questions');
                    $postArray['total_marks'] = $this->input->post('total_marks');
                }
                if ($categoryId = $this->questions_model->addCategory($postArray)) {
                    $this->result['status'] = true;
                    if ($this->input->post('is_parent') == 1) {
                        $this->questions_model->updateParentCategoryId($categoryId);
                    }
                }
            }
        }
        echo json_encode($this->result);
    }
    /*
    *Methodname:  businessModel
    *Purpose: Display questions's business model page
    */
    public function businessModel() {
        //Jqgrid
        $this->template->addCSSLink(PLUGIN . 'Jqgrid/css/jqGrid.css');
        $this->template->addCSSLink(PLUGIN . 'Jqgrid/css/jqGridCustom.css');
        //Jqgrid
        $this->template->addJSLink(PLUGIN . 'Jqgrid/js/grid.locale-en.js');
        $this->template->addJSLink(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js');
        //Bootstrap datepikcer js
        $this->template->addJSLink(JS . 'bootstrap-datepicker.js');
        $this->template->title = 'Business Model - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'questions/business_model_view', '', true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname:  businessModelGrid
    *Purpose: Display questions's business model grid
    */
    public function businessModelGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->questions_model->getBusinessModel($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->questions_model->getBusinessModel($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  addEditBusinessModel
    *Purpose: Manage add/edit functionlty through grid of business model
    */
    public function addEditBusinessModel() {
        if ($this->input->post('oper') == 'edit') {
            if ($this->questions_model->editBusinessModel()) echo json_encode(true);
            else echo json_encode(false);
        } else {
            if ($this->questions_model->addBusinessModel()) echo json_encode(true);
            else echo json_encode(false);
        }
    }
    /*
    *Methodname:  checkBusinessModel
    *Purpose: check business model name before create new
    */
    public function checkBusinessModel() {
        $businessModelName = $this->input->post('business_model_name', TRUE);
        if ($this->questions_model->checkBusinessModel($businessModelName, 'add')) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  sitequestions
    *Purpose: Display questions's items
    */
    public function sitequestions() {
        //Jqgrid
        $this->template->addCSSLink(PLUGIN . 'Jqgrid/css/jqGrid.css');
        $this->template->addCSSLink(PLUGIN . 'Jqgrid/css/jqGridCustom.css');
        //Jqgrid
        $this->template->addJSLink(PLUGIN . 'Jqgrid/js/grid.locale-en.js');
        $this->template->addJSLink(PLUGIN . 'Jqgrid/js/jquery.jqGrid.min.js');
        //Bootstrap datepikcer js
        $this->template->addJSLink(JS . 'bootstrap-datepicker.js');
        $this->template->title = 'Site questions - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'questions/site_questions_view', '', true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname:  sitequestionsGrid
    *Purpose: Display questions's grid
    */
    public function sitequestionsGrid() {
        $reqParam = array("sort_by" => $this->input->post("sidx", TRUE), "sort_direction" => $this->input->post("sord", TRUE), "page" => $this->input->post("page", TRUE), "num_rows" => $this->input->post("rows", TRUE), "search" => $this->input->post("_search", TRUE), "search_field" => $this->input->post("searchField", TRUE), "search_operator" => $this->input->post("searchOper", TRUE), "search_str" => $this->input->post("searchString", TRUE));
        $data = new stdClass();
        $data->page = $this->input->post("page", TRUE);
        $data->records = count($this->questions_model->getquestions($reqParam, "all", true)->result_array());
        $data->total = ceil($data->records / $this->input->post("rows", TRUE));
        $records = $this->questions_model->getquestions($reqParam)->result_array();
        $data->rows = $records;
        echo json_encode($data);
        exit(0);
    }
    /*
    *Methodname:  addeditquestions
    *Purpose: Manage add/edit functionlty through grid questions
    */
    public function addEditquestions() {
        if ($this->input->post('oper') == 'edit') {
            if ($this->questions_model->editquestions()) echo json_encode(true);
            else echo json_encode(false);
        } elseif ($this->input->post('oper') == 'del') {
            if ($this->questions_model->deleteById(TBL_SITE_questions, 'questions_id', $this->input->post('id', true))) echo json_encode(true);
            else echo json_encode(false);
        } else {
            if ($this->questions_model->addquestions()) echo json_encode(true);
            else echo json_encode(false);
        }
    }
    /*
    *Methodname:  checkquestionsName
    *Purpose: check questions name before create new
    */
    public function checkquestionsName() {
        $attrName = $this->input->post('attribute_name', TRUE);
        if ($this->questions_model->checkquestions($attrName, 'add')) echo json_encode(false);
        else echo json_encode(true);
    }
    /*
    *Methodname:  import
    *Purpose: import question
    */
    public function import() {
        $this->template->title = 'Import Question - ' . SITE_NAME;
        $this->template->content = $this->load->view(ADMIN . 'questions/questions_import_view', '', true);
        $this->template->renderAdminTemplate();
    }
    /*
    *Methodname:  uploadExcel
    *Purpose: import question
    */
    public function uploadexcel() {
        $config['upload_path'] = DOCS;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 2048;
        //echo $_FILES["document"]['type'] ;
        $this->load->library('upload', $config);
        $this->load->library('excel');
        if ($this->upload->do_upload('document')) {
            try {
                $upload = $this->upload->data();
                $this->load->library('excel');
                $objPHPExcel = PHPExcel_IOFactory::load(DOCS . $upload['file_name']);
                $colArray = array("1" => 'Question Category', "2" => 'Question Level', "3" => 'Question Type', "4" => 'Question', "5" => 'Option1', "6" => 'Option2', "7" => 'Option3', "8" => 'Option4', "9" => 'Option5', "10" => 'Answers',);
                $errorCount = 0;
                $successCount = 0;
                foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    for ($row = 1;$row <= $highestRow;++$row) {
                        $errorStr = array();
                        $rowValue = array();
                        $optionsArray = array();
                        for ($col = 0;$col < $highestColumnIndex;++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = trim($cell->getValue());
                            if ($row == 1) {
                                $key = array_search($val, $colArray);
                                if (!$key) throw new Exception("Uploaded files columns doesn't match with required format, please <a href='" . base_url(DOCS . 'sample.xls') . "'>click here</a> to download sample file.");
                                $checkCol = $col + 1;
                                if ($checkCol != $key) throw new Exception("Uploaded files columns doesn't match with required format, please <a href='" . base_url(DOCS . 'sample.xls') . "'>click here</a> to download sample file.");
                                $this->questions_model->deleteError($this->session->userdata('adminId'));
                            } else {
                                $rowValue[] = $val;
                                switch ($col) {
                                    case 0:
                                        //Check sub category
                                        $valArr = explode(',', $val);
                                        $catIdArr = array();
                                        $catError = 0;
                                        foreach($valArr as $vals) {
                                            $categoryDetail = $this->questions_model->checkCatName($vals);
                                            if (!$categoryDetail) {
                                                $catError = 1;
                                                $errorStr[] = $vals . ' Category not found';
                                            } else {
                                                $catIdArr[] = $categoryDetail->question_category_id;
                                            }
                                        }
                                    break;
                                    case 1:
                                        //Check diffLevel
                                        $diffLevelDetail = $this->questions_model->checkDiffLevel($val);
                                        if (!$diffLevelDetail) {
                                            $errorStr[] = $val . ' difficulty level not found';
                                        } else {
                                            $difLevelId = $diffLevelDetail->difficulty_levels_id;
                                        }
                                    break;
                                    case 2:
                                        //Check Question Type
                                        if ($val != 'Single' && $val != 'Multiple') {
                                            $errorStr[] = $val . ' Question type not found';
                                        }
                                    break;
                                    case 3:
                                        //Check Question
                                        if (!$catError) {
                                            foreach($catIdArr as $catId) {
                                                $questionDetail = $this->questions_model->checkQuestion($val, $catId);
                                                if ($questionDetail) {
                                                    $errorStr[] = ' question allready exist in ' . $catId . ' category';
                                                }
                                            }
                                        }
                                    break;
                                    case 4:
                                        //Check Options
                                        if ($val == '') $errorStr[] = 'First options not provided';
                                        else $optionsArray[] = $val;
                                        break;
                                    case 5:
                                        if ($val == '') $errorStr[] = 'Second options not provided';
                                        else $optionsArray[] = $val;
                                        break;
                                    case 6:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 7:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 8:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 9:
                                        if ($val == '') $errorStr[] = 'Answers not provided';
                                        else $answer = $val;
                                        break;
                                    default:
                                    }
                                }
                            } // End of cols for loop
                            if ($row != 1) {
                                if ($errorStr) {
                                    // if Error occur than insert in questions error
                                    $errorString = implode(",", $errorStr);
                                    $this->_addQuestionError($rowValue, $errorString);
                                } else {
                                    // If all things fine than first insert question than insert options
                                    //prepare data for question
                                    $catIdStr = implode(',', $catIdArr);
                                    if ($rowValue[2] == 'Single') $questionType = 1;
                                    elseif ($rowValue[2] == 'Multiple') $questionType = 2;
                                    $insertData['fk_difficulty_levels_id'] = $difLevelId;
                                    $insertData['sub_categories'] = $catIdStr;
                                    $insertData['answer_type'] = $questionType;
                                    $insertData['no_of_options'] = count($optionsArray);
                                    $insertData['question_description'] = $rowValue[3];
                                    $insertData['correct_answers'] = $rowValue[9];
                                    $insertData['created_date'] = date('Y-m-d H:i:s');
                                    //Insert in question table
                                    $this->db->trans_begin();
                                    $lastQuestionId = $this->questions_model->addDetails(TBL_QUESTION, $insertData);
                                    if ($lastQuestionId) {
                                        $answerArr = explode(',', $rowValue[9]);
                                        foreach($optionsArray as $key => $option) {
                                            $optionInsertData['answer_description'] = $option;
                                            $optionInsertData['fk_questions_id'] = $lastQuestionId;
                                            $optionInsertData['created_date'] = date('Y-m-d H:i:s');
                                            if (in_array($key + 1, $answerArr)) $optionInsertData['is_correct'] = 1;
                                            else $optionInsertData['is_correct'] = 0;
                                            $this->questions_model->addDetails(TBL_ANSWER, $optionInsertData);
                                            if ($this->db->trans_status() === FALSE) {
                                                $this->db->trans_rollback();
                                                $this->_addQuestionError($rowValue, 'error in adding option');
                                            } else $this->db->trans_commit();
                                        }
                                    } else {
                                        // Insert row in error tbl
                                        $this->_addQuestionError($rowValue, 'error in adding question');
                                    }
                                }
                            }
                        } // End of row for loop
                        
                    }
                    echo 'uploaded';
                }
                catch(Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                echo $this->upload->display_errors();
            }
        }
        /*
        *Methodname:  read
        *Purpose: read excel
        */
        public function read() {
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load(DOCS . "Book1.xlsx");
            $colArray = array("1" => 'Question Category', "2" => 'Question Level', "3" => 'Question Type', "4" => 'Question', "5" => 'Option1', "6" => 'Option2', "7" => 'Option3', "8" => 'Option4', "9" => 'Option5', "10" => 'Answers',);
            try {
                foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    for ($row = 1;$row <= $highestRow;++$row) {
                        $errorStr = array();
                        $rowValue = array();
                        $optionsArray = array();
                        for ($col = 0;$col < $highestColumnIndex;++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = trim($cell->getValue());
                            if ($row == 1) {
                                $key = array_search($val, $colArray);
                                if (!$key) throw new Exception("Colunm not found");
                                $checkCol = $col + 1;
                                if ($checkCol != $key) throw new Exception("Colunm not arranged properly");
                            } else {
                                $rowValue[] = $val;
                                switch ($col) {
                                    case 0:
                                        //Check sub category
                                        $valArr = explode(',', $val);
                                        $catIdArr = array();
                                        $catError = 0;
                                        foreach($valArr as $vals) {
                                            $categoryDetail = $this->questions_model->checkCatName($vals);
                                            if (!$categoryDetail) {
                                                $catError = 1;
                                                $errorStr[] = $vals . ' Category not found';
                                            } else {
                                                $catIdArr[] = $categoryDetail->question_category_id;
                                            }
                                        }
                                    break;
                                    case 1:
                                        //Check diffLevel
                                        $diffLevelDetail = $this->questions_model->checkDiffLevel($val);
                                        if (!$diffLevelDetail) {
                                            $errorStr[] = $val . ' difficulty level not found';
                                        } else {
                                            $difLevelId = $diffLevelDetail->difficulty_levels_id;
                                        }
                                    break;
                                    case 2:
                                        //Check Question Type
                                        if ($val != 'Single' && $val != 'Multiple') {
                                            $errorStr[] = $val . ' Question type not found';
                                        }
                                    break;
                                    case 3:
                                        //Check Question
                                        if (!$catError) {
                                            foreach($catIdArr as $catId) {
                                                $questionDetail = $this->questions_model->checkQuestion($val, $catId);
                                                if ($questionDetail) {
                                                    $errorStr[] = $val . ' question allready exist in ' . $catId . ' category';
                                                }
                                            }
                                        }
                                    break;
                                    case 4:
                                        //Check Options
                                        if ($val == '') $errorStr[] = 'First options not provided';
                                        else $optionsArray[] = $val;
                                        break;
                                    case 5:
                                        if ($val == '') $errorStr[] = 'Second options not provided';
                                        else $optionsArray[] = $val;
                                        break;
                                    case 6:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 7:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 8:
                                        if ($val != '') $optionsArray[] = $val;
                                        break;
                                    case 9:
                                        if ($val == '') $errorStr[] = 'Answers not provided';
                                        else $answer = $val;
                                        break;
                                    default:
                                    }
                                }
                            }
                            if ($row != 1) {
                                if ($errorStr) {
                                    // if Error occur than insert in questions error
                                    $errorString = implode(",", $errorStr);
                                    $this->_addQuestionError($rowValue, $errorString);
                                } else {
                                    // If all things fine than first insert question than insert options
                                    //prepare data for question
                                    $catIdStr = implode(',', $catIdArr);
                                    if ($rowValue[2] == 'Single') $questionType = 1;
                                    elseif ($rowValue[2] == 'Multiple') $questionType = 2;
                                    $insertData['fk_difficulty_levels_id'] = $difLevelId;
                                    $insertData['sub_categories'] = $catIdStr;
                                    $insertData['answer_type'] = $questionType;
                                    $insertData['no_of_options'] = count($optionsArray);
                                    $insertData['question_description'] = $rowValue[3];
                                    $insertData['correct_answers'] = $rowValue[9];
                                    $insertData['created_date'] = date('Y-m-d H:i:s');
                                    //Insert in question table
                                    $this->db->trans_begin();
                                    $lastQuestionId = $this->questions_model->addDetails(TBL_QUESTION, $insertData);
                                    if ($lastQuestionId) {
                                        $answerArr = explode(',', $rowValue[9]);
                                        foreach($optionsArray as $key => $option) {
                                            $optionInsertData['answer_description'] = $option;
                                            $optionInsertData['fk_questions_id'] = $lastQuestionId;
                                            $optionInsertData['created_date'] = date('Y-m-d H:i:s');
                                            if (in_array($key + 1, $answerArr)) $optionInsertData['is_correct'] = 1;
                                            else $optionInsertData['is_correct'] = 0;
                                            $this->questions_model->addDetails(TBL_ANSWER, $optionInsertData);
                                            if ($this->db->trans_status() === FALSE) {
                                                $this->db->trans_rollback();
                                                $this->_addQuestionError($rowValue, 'Error in adding option');
                                            } else $this->db->trans_commit();
                                        }
                                    } else {
                                        // Insert row in error tbl
                                        $this->_addQuestionError($rowValue, 'Error in adding question');
                                    }
                                }
                            }
                        }
                    }
                }
                catch(Exception $e) {
                    echo $e->getMessage();
                }
            }
            /*
            *Methodname:  _addQuestionError
            *Purpose: add question error in database
            */
            private function _addQuestionError($rowValue, $errorStr = '') {
                // if Error occur than insert in questions error
                $errorData['question_category'] = $rowValue[0];
                $errorData['question_level'] = $rowValue[1];
                $errorData['question_type'] = $rowValue[2];
                $errorData['question'] = $rowValue[3];
                $errorData['option1'] = $rowValue[4];
                $errorData['option2'] = $rowValue[5];
                $errorData['option3'] = $rowValue[6];
                $errorData['option4'] = $rowValue[7];
                $errorData['option5'] = $rowValue[8];
                $errorData['answers'] = $rowValue[9];
                $errorData['error_string'] = $errorStr;
                $errorData['fk_admin_id'] = $this->session->userdata('adminId');
                $this->questions_model->addDetails(TBL_QUESTION_IMPORT_ERROR, $errorData);
            }
            /*
            *Methodname:  getErrorFile
            *Purpose: Create excel
            */
            public function getErrorFile() {
                $this->load->library('excel');
                $errorDetails = $this->questions_model->getQuestionsError($this->session->userdata('adminId'));
                $objPHPExcel = new PHPExcel();
                // Set properties
                $objPHPExcel->getProperties()->setCreator(SITE_NAME)->setLastModifiedBy(SITE_NAME)->setTitle("Office 2007 XLSX Error Document")->setSubject("Office 2007 XLSX Error Document")->setDescription("Error doc for Office 2007 XLSX, generated by " . SITE_NAME)->setKeywords("office 2007")->setCategory("Error file");
                $objPHPExcel->getActiveSheet()->setTitle('Questions Error');
                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Question Category')->setCellValue('B1', 'Question Level')->setCellValue('C1', 'Question Type')->setCellValue('D1', 'Question')->setCellValue('E1', 'Option1')->setCellValue('F1', 'Option2')->setCellValue('G1', 'Option3')->setCellValue('H1', 'Option4')->setCellValue('I1', 'Option5')->setCellValue('J1', 'Answers')->setCellValue('K1', 'Errors');
                // Miscellaneous glyphs, UTF-8
                $objPHPExcel->setActiveSheetIndex(0);
                $rows = 2;
                foreach($errorDetails as $error) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rows, $error->question_category)->setCellValue('B' . $rows, $error->question_level)->setCellValue('C' . $rows, $error->question_type)->setCellValue('D' . $rows, $error->question)->setCellValue('E' . $rows, $error->option1)->setCellValue('F' . $rows, $error->option2)->setCellValue('G' . $rows, $error->option3)->setCellValue('H' . $rows, $error->option4)->setCellValue('I' . $rows, $error->option5)->setCellValue('J' . $rows, $error->answers)->setCellValue('K' . $rows, $error->error_string);
                    $rows++;
                }
                $objPHPExcel->setActiveSheetIndex(0);
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('Simple');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="error.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
            }
            /*
            *Methodname:  checkError
            *Purpose: check Error exist
            */
            public function checkError() {
                $errorDetails = $this->questions_model->getQuestionsError($this->session->userdata('adminId'));
                if ($errorDetails) echo json_encode(true);
                else echo json_encode(false);
            }
        }
        /* End of file questions.php */
        /* Location: ./application/controllers/admin/questions.php */
        