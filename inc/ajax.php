<?php
include_once 'functions.php';

if (isset($_GET['action']) && isValidAjaxRequest()) {
    sleep(1);
    if (isAdmin()) {
        $action = $_GET['action'];
        if ($action == 'qm') { // do question management
            $qmArr = explode('-', $_POST['qmStr']);
            $operation = $qmArr[0];
            $qid = $qmArr[1];
            if ($operation == 'qmd') {
                removeQuestion($qid);
                echo 'deleted';
            } elseif ($operation == 'qmpe') {
                changeQuestionStatus($qid, 'pending');
                echo 'cancel confirmation';
            } elseif ($operation == 'qmpu') {
                changeQuestionStatus($qid, 'publish');
                echo 'confirm and publish';
            }
        } else {
            // another ajax actions here
        }
    } else {
        echo "دسترسی غیر مجاز ...";
    }
} else {
    echo "درخواست نامعتبر است .";
}