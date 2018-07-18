<?php
session_start();
include_once 'config.php';

/***** Helper Functions *****/
function myPrint($var)
{
    echo '<pre class="ltr">';
    if (is_array($var) or is_object($var))
        print_r($var);
    else
        echo $var;
    echo '</pre>';
}


/***** Validation Functions *****/
function isValidAjaxRequest()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
}

function isValidStatus($status)
{
    $statusArr = array('pending', 'publish', 'answered');
    if (in_array($status, $statusArr))
        return true;
    return false;
}

function getValidStatus($status)
{
    if (isValidStatus($status)) {
        return $status;
    } else {
        return 'all';
    }
}

function isValidQuestion($qText, $uName, &$errMsg = null)
{
    $errMsg = '';
    $hasError = false;
    if (strlen($qText) < QA_QUSETION_MIN_LENGTH) {
        $errMsg .= "short answer\n";
        $hasError = true;
    }
    if (strlen($uName) < QA_UNAME_MIN_LENGTH) {
        $errMsg .= "short name\n";
        $hasError = true;
    }
    if ($hasError) {
        return false;
    }
    return true;
}

/***** Database Functions *****/
function addQuestion($uName, $qText, &$errorMsg = '')
{
    global $db;
    // sanitize all inputs in one line !!!
    list($uName, $qText) = array(sanitize($uName), sanitize($qText));
    $sql = "INSERT INTO $db->questionTable (uname, text) VALUES ('$uName', '$qText');";
    $result = $db->query($sql);
    if ($result) {
        return true;
    }
    $errorMsg = 'خطایی در هنگام ثبت سوال شما رخ داده است .';
    return false;
}

function addAnswer($qid, $aText, &$errorMsg = '')
{
    global $db;
    // sanitize all inputs in one line !!!
    list($qid, $aText) = array(sanitize($qid), sanitize($aText));
    $sql = "INSERT INTO $db->answerTable (qid, text) VALUES ('$qid','$aText');";
    $result = $db->query($sql);
    if ($result) {
        changeQuestionStatus($qid, 'answered');
        // get email of question and send a noification Email ! [using php mail() function]
        // get mobile number of question and send a noification SMS Here ! [using webservice]
        return true;
    }
    $errorMsg = 'خطایی در هنگام ثبت پاسخ شما رخ داده است .';
    return false;
}

function removeQuestion($qid)
{
    global $db;
    $sql = "Delete from $db->questionTable WHERE id=$qid;";
    $result = $db->query($sql);
    if ($result) {
        return true;
    }
    return false;
}

function removeAnswer($aid)
{
    global $db;
    $sql = "Delete from $db->answerTable WHERE id=$aid;";
    $result = $db->query($sql);
    if ($result) {
        return true;
    }
    return false;
}

function getNumQuestions($status = 'all')
{
    global $db;
    if (getValidStatus($status) != 'all') {
        $sql = "SELECT count(*) as c FROM $db->questionTable where status='$status'";
    } else {
        $sql = "SELECT count(*) as c FROM $db->questionTable";
    }
    $result = $db->query($sql);
    if ($result) {
        $record = $result->fetch_object();
        return $record->c;
    }
    return false;
}

function getQuestions($status = 'all', $search = null, $page = 1, &$numQuestions = 0)
{
    global $db;
    // page calculation
    $start = ($page - 1) * QA_QUSETION_PER_PAGE;
    $questionPerPage = QA_QUSETION_PER_PAGE;

    if ($status == 'all') {
        if (!isAdmin()) {
            $whereStr = "status!='pending'";
        } else {
            $whereStr = "1";
        }
        if ($search != null) {
            $sql = "SELECT * FROM $db->questionTable where $whereStr and text like '%$search%' order by create_date desc limit $start,$questionPerPage";
            $countSql = "SELECT count(*) as c FROM $db->questionTable where $whereStr and text like '%$search%'";
        } else {
            $sql = "SELECT * FROM $db->questionTable where $whereStr order by create_date desc limit $start,$questionPerPage";
            $countSql = "SELECT count(*) as c FROM $db->questionTable where $whereStr";
        }
    } elseif (isAdmin() or (in_array(getValidStatus($status), array('publish', 'answered')))) {
        if ($search != null) {
            $sql = "SELECT * FROM $db->questionTable where status='$status' and text like '%$search%' order by create_date desc limit $start,$questionPerPage";
            $countSql = "SELECT count(*) as c FROM $db->questionTable where status='$status' and text like '%$search%'";
        } else {
            $sql = "SELECT * FROM $db->questionTable where status='$status' order by create_date desc limit $start,$questionPerPage";
            $countSql = "SELECT count(*) as c FROM $db->questionTable where status='$status'";
        }
    } else {
        $sql = "SELECT * FROM $db->questionTable where status!='pending' order by create_date desc limit $start,$questionPerPage";
        $countSql = "SELECT count(*) as c FROM $db->questionTable where status!='pending'";
    }
    $result = $db->query($sql);
    if ($result) {
        //$questions = $result->fetch_all(1);
        while ($row = mysqli_fetch_assoc($result)) {
            $questions[] = $row;
        }
        $numQuestions = $db->query($countSql)->fetch_object()->c;
        return $questions;
    }
    return null;
}

function getAnswers($qid)
{
    global $db;
    $sql = "SELECT * FROM $db->answerTable where qid='$qid'";
    $result = $db->query($sql);
    if ($result) {
        $answers = $result->fetch_all(1);
        $str = '';
        foreach ($answers as $a) {
            $str .= '<div class="a" id="a-' . $a['id'] . '">' . nl2br($a['text']) . '<span class="date">(' . ($a['create_date']) . ')</span></div>' . PHP_EOL;
        }
        return $str;
    }
    return '';
}

function changeQuestionStatus($qid, $status)
{
    if (isValidStatus($status)) {
        global $db;
        $sql = "UPDATE $db->questionTable SET status='$status' WHERE id=$qid;";
        $result = $db->query($sql);
        if ($result) {
            return true;
        }
    }
    return false;
}

/***** Authentication(login/logout/check) Functions *****/
function doLogin($username, $password)
{
    if ($username == QA_ADMIN_USERNAME && $password == QA_ADMIN_PASSWORD) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        $_SESSION['userIP'] = $_SERVER['REMOTE_ADDR'];
        return true;
    }
    return false;
}

function doLogout()
{
    unset($_SESSION['login'], $_SESSION['user'], $_SERVER['REMOTE_ADDR']);
    return true;
}

function isAdmin()
{
    return (isset($_SESSION['login'])) ? true : false;
}

/***** Data Cleaning and Sanitizing Functions *****/
function cleanInput(&$input)
{

    $search = array(
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    $input = $output;
    return $output;
}

/**
 * @param $input
 * @return mixed
 */
function sanitize(&$input)
{
    if (is_array($input)) {
        foreach ($input as $var => $val) {
            $output[$var] = sanitize($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $output = cleanInput($input);
        //$output = mysql_real_escape_string($input);
    }
    $input = $output;
    return $output;
}

/***** Pagination Functions *****/
function getNumPages($numQuestions)
{
    $numPages = ceil($numQuestions / QA_QUSETION_PER_PAGE);
    return $numPages;
}

function getPageUrl($pageNumber)
{
    $getParameters = array();
    if (isset($_GET['status']))
        $getParameters['status'] = $_GET['status'];
    if (isset($_GET['search']))
        $getParameters['search'] = $_GET['search'];
    $getParameters['page'] = $pageNumber;
    $str = '?';
    foreach ($getParameters as $key => $value) {
        $str .= "$key=$value&";
    }
    return QA_HOME_URL . trim($str, '&');
}

