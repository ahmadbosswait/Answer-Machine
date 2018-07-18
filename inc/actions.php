<?php
include_once 'functions.php';
$errorMsg = false; // error message
$successMsg = false; // success message
if (isset($_POST['submitQuestion'])) {
    if (isValidQuestion($_POST['uQuestion'],  $_POST['uName'], $errorMsg)) {
        // add question to database ...
        if (addQuestion($_POST['uName'], $_POST['uQuestion'], $errorMsg)) {
            $successMsg = "سوال شما با موفقیت ثبت شد و منتظر بازبینی مدیر است .";
        }
    }
}

if (isset($_POST['submitAnswer']) and isAdmin()) {
    if (addAnswer($_POST['qid'], $_POST['text'], $errorMsg)) {
        $successMsg = "پاسخ با موفقیت ثبت شد .";
    }
}

if (isset($_POST['login'])) {
    if (doLogin($_POST['username'], $_POST['password'])) {
        header("Location: " . QA_HOME_URL);
    } else {
        $errorMsg = 'نام کاربری یا رمز وارد شده اشتباه است .';
    }
}


if (isset($_GET['logout'])) {
    doLogout();
}

// get questions
$questions = null;
$numQuestions = 0;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
if (isset($_GET['search']) and strlen($_GET['search']) > 0) {
    /* a simple trick : replace space with % for use in sql Like statement
     * other approach : like , fulltext , concat
     * Read this pages :  http://dev.mysql.com/doc/refman/5.0/en/fulltext-natural-language.html
                          http://www.mysqltutorial.org/mysql-full-text-search.aspx
     */
    $search = str_ireplace(' ', '%', $_GET['search']);
    if (isset($_GET['status'])) {
        $questions = getQuestions($_GET['status'], $search, 1, $numQuestions);
    } else {
        $questions = getQuestions('all', $search, 1, $numQuestions);
    }
} else {
    if (isset($_GET['status'])) {
        $questions = getQuestions(trim($_GET['status']), null, $page, $numQuestions);
    } else {
        $questions = getQuestions('all', null, $page, $numQuestions);
    }
}