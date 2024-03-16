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
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login Name</span> : <?php echo $userInfo['userName'] ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o fa-fw"></i>
                            <span>Email</span> : <?php echo $userInfo['Email'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Full Name</span> : <?php echo $userInfo['Fullname'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Registered Date</span> : <?php echo $userInfo['currDate'] ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Fav Category</span> :
                        </li>
                    </ul>
                    <a href="#" class="btn btn-default">Edit Information</a>
                </div>
            </div>
        </div>
    </div>

    <div class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Advertisements</div>
                <div class="panel-body">
                        <?php
                        $getAds = getItems('Member_ID' ,$userInfo['userID']);
                        if (!empty($getAds)) {
                            echo '<div class="row">';
                                foreach (getItems('Member_ID', $userInfo['userID']) as $item) {
                                    echo '<div class="col-sm-6 col-md-4">';
                                    echo '<div class="thumbnail item-box">';
                                    echo '<span class= price-tag>$' . $item['Price'] . '</span>';
                                    echo '<img class="img-responsive" src="img.png" alt="">';
                                    echo '<div class="caption">';
                                    echo '<h3><a href="items.php?itemID='.$item['itemID'].'">' . $item['Name'] . '</a></h3>';
                                    echo '<p>' . $item['Description'] . '</p>';
                                    echo '<div class="date">' . $item['AddedDate'] . '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            echo '</div>';
                        }else{
                            echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad</a>';
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