<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Data base tables constant
|--------------------------------------------------------------------------
|
| Need to use all constants in all over in coding
|
*/

define('TBL_ANSWER', 'tbl_answer'); 
define('TBL_QUESTION', 'tbl_questions');
define('TBL_QUESTION_CATEGORY', 'tbl_question_category');
define('TBL_TEST_USER', 'tbl_test_user');
define('TBL_USER_TEST', 'tbl_user_test');
define('TBL_USER_TEST_ANSWER', 'tbl_user_test_answer');
define('TBL_DIFFICULTY_LEVELS', 'tbl_difficulty_levels');
define('TBL_ACTIVITY_LOG', 'tbl_user_activity_log'); 
define('TBL_QUESTION_IMPORT_ERROR', 'tbl_question_import_error'); 
/*
	Test configuration rules
*/
define('NEXT_DIFF_LEVEL_MIN_CRITERIA',70);// if  more than or equal 75 then move to next diff level
define('NEXT_DIFF_LEVEL_MAX_CRITERIA',100);
define('SAME_DIFF_LEVEL_MIN_CRITERIA',50); // more than or equal 50
define('SAME_DIFF_LEVEL_MAX_CRITERIA',69.9);

define('PREVIOUS_DIFF_LEVEL_MIN_CRITERIA',0); // more than or equal 50
define('PREVIOUS_DIFF_LEVEL_MAX_CRITERIA',49.9);
define('LEVEL_CHECK_FLAG',5);
define('EXCEPTION_STR',"Internal server Error please try again");
define('DEFAULT_CATEGORY',2); // This category will have all questions
define('PARENT_CATEGORY',1); // This is the root Test
// Audit Activity Type
define('TEST_LOGIN', 'test login');
define('TEST_LOGOUT', 'test_logout');
define('TEST_REGISTRATION', 'test_registration');
define('TEST_START','test start');
/*
|--------------------------------------------------------------------------
| dir path
|--------------------------------------------------------------------------
|
| path of some files and directories
|
*/
define('ADMIN', 'admin/'); // Constant for admin directory
define('JS', 'assets/js/');
define('CSS', 'assets/css/'); 
define('IMAGES', 'assets/images/');
define('PLUGIN', 'plugin/'); // Constant for add on plugins
define('DOCS', 'uploads/documents/');
define('FLAG', 'images/flags/');
define('CAPTCHA', 'captcha/');

/*
|--------------------------------------------------------------------------
| Site Constant
|--------------------------------------------------------------------------
|
| Some site constant
|
*/
define('SITE_NAME', 'Online Skills Assessment');

//date format
define('DATE_FORMAT', 'd-M-Y H:i:s'); // Constant for admin time format
define('DATE_FORMAT_NO_TIME', 'd-M-Y'); // Constant for user time format
define('DATE_FORMAT_BOOT_STRAP', 'd-M-yyyy'); // Constant for admin time format

//Emails
define('ADMIN_EMAIL', '');
define('ADMIN_EMAIL_NO_RPLY', ''); // Constant for admin email id
define('CONTACT_EMAIL', ''); // Constant for contact mail
define('SUPPORT_EMAIL', '');

//social link
define('FACEBOOK', 'https://www.facebook.com/');
define('GPLUS', 'https://plus.google.com/s');
define('LINKEDIN', 'http://www.linkedin.com/');
define('TWITTER', 'https://www.twitter.com/');
//Address & contact details
define('ADDRESS_LINE1', 'Address line 1');
define('ADDRESS_LINE2', 'Address line 2');
define('ADDRESS_LINE3', 'Address line 3');
define('ADDRESS_LINE4', 'Address line 4');
define('PHONE', '0000000000');
define('FAX', '000000000');

/* End of file constants.php */
/* Location: ./application/config/constants.php */