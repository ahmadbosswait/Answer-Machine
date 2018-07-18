<?php
// config for Question Answering System

// Website information
define('QA_TITLE', 'Algorithms for beginners');
define('QA_HOME_URL', 'http://localhost/design-projects/answer-machine/');
define('QA_QUSETION_PER_PAGE', 5);
define('QA_QUSETION_MIN_LENGTH', 10);
define('QA_UNAME_MIN_LENGTH', 5);
define('QA_DATE_FORMAT', "d F Y");

// admin information
define('QA_ADMIN_DISPLAYNAME', 'Administrator');
define('QA_ADMIN_USERNAME', 'admin');
define('QA_ADMIN_PASSWORD', 'admin');


// turn off error reporting after project completion
ini_set('display_errors', 'On');
error_reporting(E_ALL);


// database information
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'answer_machine';

$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

/* check connection */
if ($db->connect_errno) {
    printf("Connect failed: %s\n", $db->connect_error);
    exit();
}

// define our tables for usage in code
$db->questionTable = "questions";
$db->answerTable = "answers";