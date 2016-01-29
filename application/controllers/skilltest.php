<?php
/*
* Version       : 1.0
* Filename      : skilltest.php
* Purpose       : This class is handle the user test page
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class SkillTest extends CI_Controller {
    var $isLogout = false;
    var $isAjaxCall = false;
    var $categoryTitle;
    /*
    *Methodname:  __construct
    *Purpose: Perform common action for class at load
    */
    public function __construct() {
        parent::__construct(false);
        $this->load->library('template');
        $this->load->model('skilltest_model');
        if ($this->input->is_ajax_request()) $this->isAjaxCall = true;
    }
    /*
    *Methodname:  index
    *Purpose: This function open test page
    */
    public function index() {
        if (!$this->session->userdata('testSesUserId')) redirect('/');
        $this->catagory();
    }
    /*
    *Methodname:  catagory
    *Purpose: This function open test page
    */
    public function catagory() {
        if ($this->session->userdata('testStatus')) {
            $this->session->set_flashdata('errorMsg', 'Your previous test is already running please complete previous test.');
            redirect('skilltest/run');
        }
        $categoryName = rawurldecode($this->uri->segment(3, ''));
        // if catagory name is blank, redirect to dashbard
        if ($categoryName == '') {
            $this->session->set_flashdata('errorMsg', 'Requested category not found.');
            redirect('/dashboard');
        }
        $subCategoryDetails = $this->skilltest_model->getSubCategoryDetails($categoryName);
        // if catagory not found in DB, redirect to dashbard
        if (!$subCategoryDetails) {
            $this->session->set_flashdata('errorMsg', 'Requested category not found.');
            redirect('/dashboard');
        }
        $data['subCategoryDetails'] = $subCategoryDetails;
        $this->template->title = 'Skill Test - ' . SITE_NAME;
        $this->template->content = $this->load->view('skilltest/skill_test_view', $data, true);
        $this->template->renderTemplate();
    }
    /*
    *Methodname:  run
    *Purpose: This function open questions with option
    */
    public function run() {
        if (!$this->session->userdata('testSesUserId')) redirect('dashboard');
        // If test status not set than redirect to report
        if (!$this->session->userdata('testStatus')) {
            $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
            redirect('report');
        }
        // If currentTestId not set than out from test
        if (!$this->session->userdata('userTestId')) {
            $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
            redirect('dashboard');
        }
        if (!$this->skilltest_model->checkUserTestStatus($this->session->userdata('userTestId'), $this->session->userdata('testSesUserId'))) {
            $this->session->set_flashdata('errorMsg', 'Test have been completed.');
            redirect('report/');
        }
        if (!$this->skilltest_model->getTestDetails($this->session->userdata('userTestId'), $this->session->userdata('testSesUserId'))) {
            $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
            redirect('dashboard/');
        }
        // condition for first question
        if ($this->session->userdata('noOFQuestionsGiven') == 0) {
            // Get question id JSON from tbl_user_test
            $questionJson = $this->skilltest_model->getQuestionId($this->session->userdata('userTestId'));
            if (!$questionJson) {
                $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
                $this->destroyTestSession();
                redirect('/');
            }
            // Take least id from json
            $questionJson = json_decode($questionJson->questions_json, TRUE);
            $questionsStr = $questionJson[$this->session->userdata('currentLevel') ];
            $questionIds = explode(',', $questionsStr);
            if ($questionIds[0] == '' || $questionIds[0] == 0) {
                $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
                $this->destroyTestSession();
                redirect('dashboard');
            }
            // get quession from db using current id
            $questionDetails = $this->skilltest_model->getQuestionDetails($questionIds[0]);
            if (!$questionDetails) {
                $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
                $this->destroyTestSession();
                redirect('dashboard');
            }
            $this->session->set_userdata('noOFQuestionsGiven', 1);
            $this->session->set_userdata('lastQuestionID', $questionIds[0]);
            $this->session->set_userdata('currentLevelnoOFQuestionsGiven', 1);
            $this->session->set_userdata('currentFlagnoOFQuestionsGiven', 1);
            $data['questionDetails'] = $questionDetails;
        } else {
            if ($this->session->userdata('lastQuestionID') != 0) {
                // get quession from db using current id
                $questionDetails = $this->skilltest_model->getQuestionDetails($this->session->userdata('lastQuestionID'));
                if (!$questionDetails) {
                    $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
                    $this->destroyTestSession();
                    redirect('dashboard');
                }
                $data['questionDetails'] = $questionDetails;
            } else {
                $this->session->set_userdata('noOFQuestionsGiven', 0);
                $this->session->set_userdata('lastQuestionID', 0);
                $this->session->set_userdata('currentLevelnoOFQuestionsGiven', 0);
                $this->session->set_userdata('currentFlagnoOFQuestionsGiven', 0);
                $this->session->set_flashdata('errorMsg', 'Test is not configured properly, please contact to Administrator.');
                redirect('dashboard');
            }
        }
        $this->template->title = 'Test Run- ' . SITE_NAME;
        $this->template->content = $this->load->view('skilltest/skill_test_run_view', $data, true);
        $this->template->renderTemplate();
    }
    /*
    *Methodname:  endTest
    *Purpose: This function is used to end user test
    */
    public function endTest() {
        $response = array();
        $response['status'] = false;
        try {
            if (!$this->isAjaxCall) {
                redirect('/');
            } else {
                if (!$this->session->userdata('testSesUserId')) $this->isLogout = true;
            }
            if ($this->isLogout) {
                $isDestroySession = true;
                $response['isLogout'] = true;
                throw new Exception('You have been signed off! please login again..');
            }
            if (!$this->session->userdata('userTestId')) {
                $isDestroySession = true;
                throw new Exception('There is no test running currently');
            }
            $response['isLogout'] = false;
            $userTestMarks = 0;
            // Calculate user test marks and update on user test table
            $totalUserTestMerks = $this->skilltest_model->calculateTestMarks($this->session->userdata('userTestId'));
            if (!$totalUserTestMerks) $userTestMarks = 0;
            else $userTestMarks = $totalUserTestMerks->total_marks;
            // Update test status to complete
            $UpdateData = array('test_status' => 1, 'marks_obtained' => $totalUserTestMerks->total_marks);
            $this->skilltest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $this->session->userdata('userTestId'), $UpdateData);
            $response['test_id'] = $this->session->userdata('userTestId');
            $this->destroyTestSession();
            $response['status'] = true;
        }
        // End of try
        catch(Exception $e) {
            if ($isDestroySession) $this->destroyTestSession();
            $response['status'] = false;
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
    }
    /*
    *Methodname:  submitAnswer
    *Purpose: This function is used to take user answer,calculate difficulty level and give next question
    */
    public function submitAnswer() {
        $response = array();
        $response['status'] = false;
        $response['result'] = false;
        $response['is_time_finished'] = false;
        $response['is_test_completed'] = false;
        $response['refresh'] = false;
        $isDestroySession = false;
        $response['test_id'] = false;
        try {
            if (!$this->isAjaxCall) {
                redirect('/');
            } else {
                if (!$this->session->userdata('testSesUserId')) $this->isLogout = true;
            }
            if ($this->isLogout) {
                $isDestroySession = true;
                $response['isLogout'] = true;
                throw new Exception('You have been signed off! please login again..');
            }
            if (!$this->session->userdata('userTestId')) {
                $isDestroySession = true;
                throw new Exception('There is some problem to setup test please start again');
            }
            $response['isLogout'] = false;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('answer_type', 'Answer Type', 'trim|required');
            $this->form_validation->set_rules('question_id', 'Question', 'trim|required|numeric');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');
            $this->form_validation->set_message('required', 'Required');
            if ($this->form_validation->run() == FALSE) {
                $isDestroySession = true;
                throw new Exception('Might be some problem Please try after some time');
            }
            $questionId = $this->input->post('question_id', TRUE);
            $answerType = $this->input->post('answer_type', TRUE);
            $status = $this->input->post('status', TRUE);
            $answer = $this->input->post('answer', TRUE);
            $timeSpent = $this->input->post('time_spent', TRUE);
            $currentLevel = $this->session->userdata('currentLevel');
            $marksObtained = 0;
            $skipped = 0;
            $answerStr = $answer;
            if ($this->session->userdata('lastQuestionID') != $questionId) {
                $response['refresh'] = true;
                throw new Exception('There is some problem, Please refresh page.');
            }
            // Check answer given time
            if (!$this->skilltest_model->checkTestTime($timeSpent, $this->session->userdata('userTestId'))) {
                $userTestMarks = 0;
                // Calculate user test marks and update on user test table
                $totalUserTestMerks = $this->skilltest_model->calculateTestMarks($this->session->userdata('userTestId'));
                if (!$totalUserTestMerks) $userTestMarks = 0;
                else $userTestMarks = $totalUserTestMerks->total_marks;
                $response['is_time_finished'] = true;
                // Update test status to complete
                $UpdateData = array('test_status' => 1, 'marks_obtained' => $totalUserTestMerks->total_marks);
                $this->skilltest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $this->session->userdata('userTestId'), $UpdateData);
                $response['test_id'] = $this->session->userdata('userTestId');
                $this->destroyTestSession();
                throw new Exception("Time has been elapsed");
            }
            if (!$this->skilltest_model->checkUserTestStatus($this->session->userdata('userTestId'), $this->session->userdata('testSesUserId'))) {
                $response['is_test_completed'] = true;
                $response['test_id'] = $this->session->userdata('userTestId');
                $this->destroyTestSession();
                throw new Exception("Test is completed");
            }
            // Check if user skip question
            if ($answer != '') {
                // Calculate marks according question type
                $answer = explode(",", $answer);
                $ansertDetails = $this->skilltest_model->getQuestionDetails($questionId);
                $marksPerQuestion = $currentLevel / $ansertDetails[0]->no_of_options;
                foreach($ansertDetails as $answerDetail) {
                    if (in_array($answerDetail->answer_id, $answer)) {
                        if ($answerDetail->is_correct == 1) $marksObtained = $marksObtained + $marksPerQuestion;
                        else $marksObtained = $marksObtained - $marksPerQuestion;
                    } else {
                        if (!$answerDetail->is_correct == 1) $marksObtained = $marksObtained + $marksPerQuestion;
                        else $marksObtained = $marksObtained - $marksPerQuestion;
                    }
                }
            } else {
                $skipped = 1;
            }
            if ($marksObtained < 0) $marksObtained = 0;
            // Insert Marks in db
            $insertData = array('fk_questions_id' => $questionId, 'fk_user_test_id' => $this->session->userdata('userTestId'), 'selected_answers' => $answerStr, 'marks_obtained' => $marksObtained, 'is_skip' => $skipped, 'created_date' => date('Y-m-d H:i:s'));
            if (!$this->skilltest_model->addDetails(TBL_USER_TEST_ANSWER, $insertData)) {
                $isDestroySession = true;
                throw new Exception(EXCEPTION_STR);
            }
            // Get question id JSON from tbl_user_test and remove current given question id
            $questionJson = $this->skilltest_model->getQuestionId($this->session->userdata('userTestId'));
            $questionJson = json_decode($questionJson->questions_json, TRUE);
            foreach($questionJson as $key => $value) {
                if ($key == $currentLevel) {
                    $questionsStr = $questionJson[$this->session->userdata('currentLevel') ];
                    $questionIds = explode(',', $questionsStr);
                    unset($questionIds[0]);
                    $questionsStr = implode(',', $questionIds);
                    $questionsArray[$key] = $questionsStr;
                } else {
                    $questionsArray[$key] = $value;
                }
            }
            //Update user test table for queston json, marks, question attampted
            if ($answerStr != '') $this->skilltest_model->updateUserTest($this->session->userdata('userTestId'), json_encode($questionsArray), 1, $marksObtained, $timeSpent);
            else $this->skilltest_model->updateUserTest($this->session->userdata('userTestId'), json_encode($questionsArray), 1, $marksObtained, $timeSpent);
            // Check test completion condition
            if ($this->skilltest_model->checkTestCompleted($this->session->userdata('userTestId'))) {
                // Calculate user test marks and update on user test table
                $totalUserTestMerks = $this->skilltest_model->calculateTestMarks($this->session->userdata('userTestId'));
                if (!$totalUserTestMerks) {
                    throw new Exception(EXCEPTION_STR);
                }
                //Update test status to completed
                $UpdateData = array('test_status' => 1, 'marks_obtained' => $totalUserTestMerks->total_marks);
                $this->skilltest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $this->session->userdata('userTestId'), $UpdateData);
                $response['status'] = true;
                $response['result'] = true;
                $response['content'] = '';
                $response['test_id'] = $this->session->userdata('userTestId');
                $this->destroyTestSession();
            } else {
                /* get question id for next question
                Update Session for noOFQuestionsGiven + 1 , lastQuestionID = selected question id
                currentLevel = calculated level,
                currentLevelQuestions
                */
                $givenLevelArr = array();
                if ($this->session->userdata('currentLevelnoOFQuestionsGiven') == $this->session->userdata('currentLevelQuestions')) {
                    // move to next level
                    $difficultyLevelsArr = json_decode($this->session->userdata('difficultyLevels'), TRUE);
                    $keys = array_keys($difficultyLevelsArr);
                    $lastLevel = end($keys);
                    if ($this->session->userdata('currentLevel') != $lastLevel) {
                        foreach($difficultyLevelsArr as $key => $value) {
                            if ($key == $this->session->userdata('currentLevel')) {
                                $next = $keys[(array_search($this->session->userdata('currentLevel'), $keys) + 1) ];
                                if ($next != '') {
                                    $nextValue = $difficultyLevelsArr[$next];
                                    $this->session->set_userdata('currentLevel', $next);
                                    $this->session->set_userdata('currentLevelQuestions', $nextValue);
                                }
                                break;
                            }
                        }
                    }
                    $this->session->set_userdata('currentLevelnoOFQuestionsGiven', 0);
                    $this->session->set_userdata('currentFlagnoOFQuestionsGiven', 0);
                }
                // After calculate level take question from calculate level
                $questionJson = $this->skilltest_model->getQuestionId($this->session->userdata('userTestId'));
                if (!$questionJson) {
                    $isDestroySession = true;
                    throw new Exception("Configuration error please contact with admin 414");
                }
                // Take least id from json
                $questionJson = json_decode($questionJson->questions_json, TRUE);
                $questionsStr = $questionJson[$this->session->userdata('currentLevel') ];
                $questionIds = explode(',', $questionsStr);
                // get quession from db using current id
                if (isset($questionIds[0]) and $questionIds[0] != '') {
                    $questionDetails = $this->skilltest_model->getQuestionDetails($questionIds[0]);
                    if (!$questionDetails) {
                        $isDestroySession = true;
                        throw new Exception("Configuration error please contact with admin");
                    }
                    $response['question_id'] = $questionIds[0];
                    $response['answer_type'] = $questionDetails[0]->answer_type;
                    $this->session->set_userdata('noOFQuestionsGiven', $this->session->userdata('noOFQuestionsGiven') + 1);
                    $this->session->set_userdata('lastQuestionID', $questionIds[0]);
                    $this->session->set_userdata('currentLevelnoOFQuestionsGiven', $this->session->userdata('currentLevelnoOFQuestionsGiven') + 1);
                    $this->session->set_userdata('currentFlagnoOFQuestionsGiven', $this->session->userdata('currentFlagnoOFQuestionsGiven') + 1);
                    $content = '';
                    $count = 1;
                    foreach($questionDetails as $question) {
                        // Show question to user
                        if ($count == 1) {
                            $content.= '<h1><span class="ques-no">Question ' . $this->session->userdata('noOFQuestionsGiven') . ':</span> ' . $question->question_description . '</h1> 
                       <ul class="test-detail">';
                        }
                        // Check Question type and give option to user
                        if ($question->answer_type == 1) {
                            // display radio button
                            $content.= '<li><input type="radio" id="answer_' . $question->answer_id . '" value="' . $question->answer_id . '" name="answer"> ' . $question->answer_description . ' 
                             </li>';
                        } else { // display check box
                            $content.= '<li><input type="checkbox" id="answer_' . $question->answer_id . '" value="' . $question->answer_id . '" name="answer">' . $question->answer_description . ' 
                             </li>';
                        }
                        $count++;
                    } // End of foreach
                    $response['content'] = $content;
                    $response['status'] = true;
                } else {
                    $isDestroySession = true;
                    throw new Exception("Configuration error please contact with admin");
                }
            }
        } // End of try
        catch(Exception $e) {
            if ($isDestroySession) $this->destroyTestSession();
            $response['status'] = false;
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
    }
    /*
    *Methodname:  preRequisites
    *Purpose: This function set all session for test
    */
    public function preRequisites() {
        $response = array();
        $response['status'] = false;
        $response['isTestRunning'] = false;
        try {
            if (!$this->isAjaxCall) {
                redirect('/');
            } else {
                if (!$this->session->userdata('testSesUserId')) $this->isLogout = true;
            }
            if ($this->isLogout) {
                $response['isLogout'] = true;
                throw new Exception('You have been signed off! please login again..');
            } else {
                $response['isLogout'] = false;
                if ($this->session->userdata('testStatus')) {
                    $response['isTestRunning'] = true;
                    throw new Exception('Your previous test is already running please complete previous test or logout and login again to start new test');
                }
                $this->load->library('form_validation');
                $this->form_validation->set_rules('cat_title', 'Test', 'trim|required');
                $this->form_validation->set_message('required', 'Required');
                if ($this->form_validation->run() == FALSE) throw new Exception('Might be some problem please try after some time');
                else {
                    $this->categoryTitle = $this->input->post('cat_title', TRUE);
                    $subCategoryDetails = $this->skilltest_model->getSubCategoryDetails($this->categoryTitle);
                    // if catagory not found in DB
                    if (!$subCategoryDetails) throw new Exception('Might be some problem please try after some time');
                    else {
                        if ($subCategoryDetails->difficulty_levels == '[]') throw new Exception('Might be some problem please try after some time');
                        // Now set test data in database
                        $testStartDate = date('Y-m-d H:i:s');
                        $insertData = array('fk_user_id' => $this->session->userdata('testSesUserId'), 'fk_question_category_id' => $subCategoryDetails->question_category_id, 'no_of_question' => $subCategoryDetails->no_of_questions, 'test_duration' => $subCategoryDetails->test_duration, 'max_marks' => $subCategoryDetails->total_marks, 'test_title' => $subCategoryDetails->category_title, 'test_difficulty_levels' => $subCategoryDetails->difficulty_levels, 'questions_attempted' => 0, 'time_spent' => 0, 'test_status' => 0, 'created_date' => $testStartDate);
                        if ($userTestId = $this->skilltest_model->addDetails(TBL_USER_TEST, $insertData)) {
                            // If all things is right than set all required session
                            $this->session->set_userdata('testStartTime', $testStartDate);
                            $this->session->set_userdata('userTestId', $userTestId);
                            $this->session->set_userdata('testStatus', 1);
                            $this->session->set_userdata('testName', $this->categoryTitle);
                            $this->session->set_userdata('subCategoryId', $subCategoryDetails->question_category_id);
                            $this->session->set_userdata('difficultyLevels', $subCategoryDetails->difficulty_levels);
                            $this->session->set_userdata('testDuration', $subCategoryDetails->test_duration);
                            $difficultyLevels = $subCategoryDetails->difficulty_levels;
                            $difficultyLevels = json_decode($difficultyLevels, TRUE);
                            $prevTotal = 0;
                            $levelCount = 0;
                            $totalQuestionsArray = "";
                            $questionsArray = "";
                            foreach($difficultyLevels as $key => $value) {
                                // For first level
                                if ($levelCount == 0) {
                                    $this->session->set_userdata('currentLevel', $key);
                                    $this->session->set_userdata('currentLevelQuestions', $value);
                                    $prevTotal = $prevTotal + $value;
                                    $totalQuestionsArray[$key] = $value;
                                    // Get random question array by category,level and limit for first level
                                    $questionDetails = $this->skilltest_model->getLevelQuestions($subCategoryDetails->question_category_id, $key, $value);
                                    $arr = explode(",", $questionDetails->questions_ids);
                                    if (count($arr) < $value) throw new Exception('Problem in setup test please try after some time.');
                                    if ($questionDetails) {
                                        $questionsArray[$key] = $questionDetails->questions_ids;
                                    } else {
                                        throw new Exception('Problem in setup test please try after some time.');
                                        break;
                                    }
                                } else { // For rest of level
                                    $limitOfcurLevel = $subCategoryDetails->no_of_questions - $prevTotal;
                                    // Get random question array by category,level and limit for rest of level
                                    $questionDetails = $this->skilltest_model->getLevelQuestions($subCategoryDetails->question_category_id, $key, $value);
                                    $arr = explode(",", $questionDetails->questions_ids);
                                    if (count($arr) < $value) throw new Exception('Problem in setup test please try after some time.');
                                    $totalQuestionsArray[$key] = $value;
                                    if ($questionDetails) {
                                        $questionsArray[$key] = $questionDetails->questions_ids;
                                    } else {
                                        throw new Exception('Might be some problem please try after some time.');
                                        break;
                                    }
                                    $prevTotal = $prevTotal + $value;
                                }
                                $levelCount++;
                            }
                            $this->session->set_userdata('totalQuestions', $subCategoryDetails->no_of_questions);
                            $this->session->set_userdata('noOFQuestionsGiven', 0);
                            $this->session->set_userdata('lastQuestionID', 0);
                            if ($levelCount != 0) {
                                if (json_encode($questionsArray) == '') {
                                    throw new Exception('Problem in setup test please try after some time.');
                                    break;
                                }
                                //Insert all level question id in database
                                $UpdateData = array('questions_json' => json_encode($questionsArray), 'total_questions_json' => json_encode($totalQuestionsArray), 'test_status' => 0);
                                $this->session->userdata('userTestId');
                                $this->skilltest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $this->session->userdata('userTestId'), $UpdateData);
                            }
                            // Add activity log for user test start
                            $this->skilltest_model->saveActivityLog($this->session->userdata('testSesUserId'), TEST_START, 'Test start by user, Test name: ' . $subCategoryDetails->category_title, TBL_USER_TEST, $this->session->userdata('userTestId'));
                            $response['status'] = true;
                        } else throw new Exception('Might be some problem Please try after some time.');
                    }
                }
            }
        }
        catch(Exception $e) {
            if ($response['isTestRunning'] != true) $this->destroyTestSession();
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
    }
    /*
    *Methodname:  setTestStatus
    *Purpose: this function is used to set test status to 1
    */
    public function setTestStatus() {
        $response = array();
        $response['status'] = false;
        $response['test_id'] = false;
        try {
            if (!$this->isAjaxCall) {
                redirect('/');
            } else {
                if (!$this->session->userdata('testSesUserId')) $this->isLogout = true;
            }
            if ($this->isLogout) {
                $response['isLogout'] = true;
                throw new Exception('You have been signed off! please login again..');
            }
            if (!$this->session->userdata('userTestId')) throw new Exception('You have been signed off! please login again..');
            $response['isLogout'] = false;
            $userTestMarks = 0;
            // Calculate user test marks and update on user test table
            $totalUserTestMerks = $this->skilltest_model->calculateTestMarks($this->session->userdata('userTestId'));
            if (!$totalUserTestMerks) $userTestMarks = 0;
            else $userTestMarks = $totalUserTestMerks->total_marks;
            //Update test status to completed
            $UpdateData = array('test_status' => 1, 'marks_obtained' => $userTestMarks);
            $this->skilltest_model->updateDetail(TBL_USER_TEST, 'user_test_id', $this->session->userdata('userTestId'), $UpdateData);
            $response['status'] = true;
        }
        catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }
        $response['test_id'] = $this->session->userdata('userTestId');
        $this->destroyTestSession();
        echo json_encode($response);
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
        $this->session->set_userdata('givenLevel', false);
    }
    /*
    *Methodname:  start
    *Purpose: start test if user already paid for test
    */
    public function start() {
        if (!$this->session->userdata('testSesUserId')) redirect('/');
	    $categoryName = rawurldecode($this->uri->segment(3, ''));
        $subCategoryDetails = $this->skilltest_model->getSubCategoryDetails($categoryName);
        if (!$subCategoryDetails) {
            $this->session->set_flashdata('errorMsg', 'Requested category not found.');
            redirect('dashboard');
        }
        $userTestStatus = $this->skilltest_model->checkUserTestPaymentStatus($subCategoryDetails->question_category_id, $this->session->userdata('testSesUserId'));
        if ($userTestStatus) {
            //load thankyou page
            $this->template->title = 'Skill Test - ' . SITE_NAME;
            $data['paymentDetail'] = $userTestStatus;
            $data['subCategoryDetails'] = $subCategoryDetails;
            $this->template->content = $this->load->view('skilltest/start_view', $data, true);
            $this->template->renderTemplate();
        } else {
            $this->session->set_flashdata('errorMsg', 'Url does not seems correct.');
            redirect('dashboard');
        }
    }
}
/* End of file skilltest.php */
/* Location: ./applicatio/controllers/skilltest.php */