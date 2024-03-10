<?php
ob_start();
session_start();
$pageTitle = "Profile";
include 'init.php';
if (isset($_SESSION['user'])) {
    $getUser = $dbconc->prepare('SELECT * FROM users WHERE userName = ?');
    $getUser->execute(array($sessionUser));
    $userInfo = $getUser->fetch();
?>
    <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Information</div>
                <div class="panel-body">
                    Name: <?php echo $userInfo['userName'] ?> <br>
                    Email: <?php echo $userInfo['Email'] ?> <br>
                    Full name: <?php echo $userInfo['Fullname'] ?> <br>
                    Register Date: <?php echo $userInfo['currDate'] ?> <br>
                    Favourite category:
                </div>
            </div>
        </div>
    </div>

    <div class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Advertisements</div>
                <div class="panel-body">
                    <div class="row">
                        <?php

                        foreach (getItems('Member_ID' ,$userInfo['userID']) as $item) {
                            echo '<div class="col-sm-6 col-md-4">';
                            echo '<div class="thumbnail item-box">';
                            echo '<span class= price-tag>$' . $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="img.png" alt="">';
                            echo '<div class="caption">';
                            echo '<h3>' . $item['Name'] . '</h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-comments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Latest Comments</div>
                <div class="panel-body">
                    <?php
                    $stmt = $dbconc->prepare("SELECT comment FROM comments where user_id = ?");
                    $stmt->execute(array($userInfo['userID']));
                    $comments = $stmt->fetchAll();

                    if (!empty($comments)) {
                        foreach ($comments as $comment) {
                            echo '<p>'. $comment['comment'] .'</p>';
                        }
                    }else{
                        echo '<p>No comments</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
}else{
    header("location:login.php");
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>