<?php
ob_start();
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
    $numComments = 4;
    ?>
    <div class="container home-stats text-center">
        <h1> Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"> <?php echo checkItem2('userID', 'users') ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus', 'users', 0) ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"> <?php echo checkItem2('itemID', 'items') ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span>200</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $latestUsers = 5; ?>
                        <i class="fa fa-users"></i> Latest <?php echo $latestUsers; ?> Registered Users
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                            $latest = getLatest('*', 'users', 'userID', 5);
                            foreach ($latest as $late) {
                                echo '<li>';
                                echo $late['userName'];
                                echo '<a href="members.php?do=Edit&ID=' . $late['userID'] . '">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit"></i> Edit';
                                if ($late['RegStatus'] == 0) {
                                    echo "<a 
																	href='members.php?do=Activate&userid=" . $late['userID'] . "' 
																	class='btn btn-info pull-right activate'>
																	<i class='fa fa-check'></i> Activate</a>";
                                }
                                echo '</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php $numItems = 5; ?>
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?>  Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                            <?php
                            $latestItems = getLatest('*', 'items', 'itemID', 5);
                            foreach ($latestItems as $lateItem) {
                                echo '<li>';
                                echo $lateItem['Name'];
                                echo '<a href="items.php?do=Edit&itemID=' . $lateItem['itemID'] . '">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit"></i> Edit';
                                if ($lateItem['Approve'] == 0) {
                                    echo "<a 
                                            href='items.php?do=Approve&itemID=" . $lateItem['itemID'] . "' 
                                            class='btn btn-info pull-right activate'>
                                            <i class='fa fa-check'></i> Approve</a>";
                                }
                                        echo '</span>';
                                    echo '</a>';
                                echo '</li>';
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Latest Comments -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i>
                            Latest <?php echo $numComments ?> Comments
                            <span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $dbconc->prepare("SELECT comments.*, users.userName AS Member  
															FROM 
																comments
															INNER JOIN 
																users 
															ON 
																users.userID = comments.user_id
															ORDER BY 
																c_id DESC
															LIMIT $numComments");

                            $stmt->execute();
                            $comments = $stmt->fetchAll();

                            if (! empty($comments)) {
                                foreach ($comments as $comment) {
                                    echo '<div class="comment-box">';
                                    echo '<span class="member-n">
													<a href="members.php?do=Edit&ID=' . $comment['user_id'] . '">
														' . $comment['Member'] . '</a></span>';
                                    echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                    echo '</div>';
                                }
                            } else {
                                echo 'There\'s No Comments To Show';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Latest Comments -->
        </div>
    </div>
    <?php
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}

ob_end_flush();
?>