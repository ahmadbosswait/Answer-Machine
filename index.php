<?php include_once "inc/actions.php" ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo QA_TITLE; ?></title>
    <link rel="stylesheet" href="css/pure.css" type="text/css"/>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>
<body>
<?php if (isAdmin()): ?>
    <div class="info">
        <span class="adminName">dear <?php echo QA_ADMIN_DISPLAYNAME; ?> welcome</span>
        <a class="logout" href="<?php echo QA_HOME_URL . '?logout=1'; ?>">Log-out</a>
    </div>
<?php endif; ?>
<div class="main">
    <?php
    if ($errorMsg) {
        echo "<div class='error'>" . nl2br($errorMsg) . "</div>";
    } elseif ($successMsg) {
        echo "<div class='success'>" . nl2br($successMsg) . "</div>";
    }
    ?>
    <div class="pure-g">
        <div class="pure-u-1 header">
            <div class="inner">
                <a href="<?php echo QA_HOME_URL; ?>"><h1><?php echo QA_TITLE; ?></h1></a>

                <form action="" method="get" class="pure-form searchform">
                    <select name="status">
                        <option value="all"
                                <?php if (!empty($_GET['status']) && $_GET['status'] == "all") { ?>selected<?php } ?>>
                            All
                        </option>
                        <?php if (isAdmin()): ?>
                            <option value="pending"
                                    <?php if (!empty($_GET['status']) && $_GET['status'] == "publish") { ?>selected<?php } ?>>
                                Pending
                            </option>
                        <?php endif; ?>
                        <option value="publish"
                                <?php if (!empty($_GET['status']) && $_GET['status'] == "publish") { ?>selected<?php } ?>>
                            Publish
                        </option>
                        <option value="answered"
                                <?php if (!empty($_GET['status']) && $_GET['status'] == "answered") { ?>selected<?php } ?>>
                            Answered
                        </option>
                    </select>
                    <input type="text" name="search" id="s"/>
                    <button class="pure-button button-green">search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="pure-g">
        <div class="pure-u-1-5 sidebar">
            <div class="inner">
                <div class="menu">
                    <div class="menu-title">Question:</div>
                    <div class="menu-content">
                        <form action="" method="post" class="pure-form searchform">
                            <input type="text" name="uName" placeholder="Your Name"/>
                            <textarea type="text" name="uQuestion" placeholder="Question"></textarea>
                            <input type="submit" name="submitQuestion" value="Submit"
                                   class="pure-button button-green">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="pure-u-4-5 content">
            <div class="inner">
                <div class="qTitle">List of Questions: <?php echo $numQuestions; ?></div>
                <?php
                if ($questions == null) {
                    echo '
                    <div class="notfound">No things found<br><br>
                    <a href="' . QA_HOME_URL . '">Return to main page</a>
                    </div>';
                } else {
                ?>
                <?php foreach ($questions as $q): ?>
                    <div class="question " id="q-<?php echo $q['id'] ?>">
                        <?php if (isAdmin()): ?>
                            <div class="qManage br5">
                                <span class="result" style="display: none">...</span>
                                <span class="qmr">Answer</span> &nbsp; &nbsp;
                                <span class="qm" id="qmd-<?php echo $q['id'] ?>">delete</span> &nbsp; &nbsp;
                                <?php if ($q['status'] != 'answered') { ?>
                                    <span class="qm" id="qmpu-<?php echo $q['id'] ?>">publish</span> &nbsp; &nbsp;
                                    <span class="qm" id="qmpe-<?php echo $q['id'] ?>">pending</span>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <div class="q"><span class="i">+</span><?php echo $q['text'] ?>?
                            <span class="date">(<?php echo $q['uname'] . '&nbsp;' .
                                    date(QA_DATE_FORMAT, strtotime($q['create_date'])); ?>
                                )</span>
                            <img src="images/<?php echo $q['status'] ?>.png" alt="">
                        </div>
                        <?php if (isAdmin()): ?>
                            <div class="r">
                                <form action="" method="post" class="pure-form replyForm">
                                    <input name="qid" value="<?php echo $q['id'] ?>" type="hidden"/>
                                    <textarea name="text" rows="5" placeholder="Answer ..."></textarea>
                                    <input type="submit" name="submitAnswer" class="pure-button button-green submit"
                                           value="Send answer"/>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        <?php endif; ?>
                        <?php if ($q['status'] == 'answered') {
                            echo getAnswers($q['id']);
                        } else if ($q['status'] == 'publish') {
                            echo '<div class="a">No Answer Yet ! !</div>';
                        } else if ($q['status'] == 'pending' and isAdmin()) {
                            echo '<div class="a">this Question not confirm yet !</div>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php } ?>
            <div class="pagination clearfix">
                <?php $numPages = getNumPages($numQuestions); ?>
                <a href="<?php echo getPageUrl(1); ?>">«</a>
                <?php for ($i = 1; $i <= $numPages; $i++) {
                    if ($i == $page) {
                        echo "<strong>$i</strong>";
                    } else {
                        echo "<a href='" . getPageUrl($i) . "'>$i</a>";
                    }
                } ?>
                <a href="<?php echo getPageUrl($numPages); ?>">»</a>
            </div>
        </div>
    </div>

    <div class="pure-g">
        <div class="pure-u-1 footer">
            <div class="inner">@2017, All Right reserved</div>
        </div>
    </div>
</div>


<script src="js/jquery.min.js"></script>
<script src="js/scripts.js"></script>
<?php if (isAdmin()): ?>
    <script src="js/admin.js"></script>
<?php endif; ?>
</body>
</html>