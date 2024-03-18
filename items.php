<?php
ob_start();
session_start();
$pageTitle = "Show Items";
include 'init.php';
// Check if id is numeric and get it's integer val
$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;
$stmt = $dbconc->prepare("SELECT 
                                    items.* , 
                                    categories.catName AS category_name,
                                    users.userName 
                                From 
                                    items 
                                Inner Join
                                    categories
                                ON 
                                    items.Cat_ID = categories.catID
                                INNER JOIN 
                                        users
                                ON 
                                    items.Member_ID = users.userID
                                WHERE 
                                    itemID = ?
                                AND
                                    Approve = 1");
$stmt->execute(array($itemID));
$count = $stmt->rowCount();
if ($count > 0) {
    $item = $stmt->fetch(); //Return data as Array
?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="img.png" alt="" />
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span> : <?php echo $item['AddedDate'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price</span> : <?php echo $item['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span> : <a href="category.php?pageID=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span> : <a href="#"><?php echo $item['userName'] ?></a>
                    </li>
                    <li class="tags-items">
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags</span> :
                        <?php
                        $allTags = explode(",", $item['tags']);
                        foreach ($allTags as $tag) {
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if (!empty($tag)) {
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                            }
                        }
                        //                    
                        ?>
                        <!--                </li>-->
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if (isset($_SESSION['user'])) { ?>
            <!-- Start Add Comment -->
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemID=' . $item['itemID'] ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input class="btn btn-primary" type="submit" value="Add Comment">
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                            $comment     = filter_var($_POST['comment'], FILTER_UNSAFE_RAW);
                            $item_id     = $item['itemID'];
                            $userid     = $_SESSION['uid'];

                            if (!empty($comment)) {

                                $stmt = $dbconc->prepare("INSERT INTO 
								comments(comment, status, comment_date, item_id, user_id)
								VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

                                $stmt->execute(array(

                                    'zcomment' => $comment,
                                    'zitemid' => $item_id,
                                    'zuserid' => $userid

                                ));

                                if ($stmt) {

                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }
                            } else {

                                echo '<div class="alert alert-danger">You Must Add Comment</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Add Comment -->
        <?php } else {
            echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
        } ?>
        <hr class="custom-hr">
        <?php

        // Select All Users Except Admin
        $stmt = $dbconc->prepare("SELECT 
										comments.*, users.userName AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.userID = comments.user_id
									WHERE 
										item_id = ?
									AND 
										status = 1
									ORDER BY 
										c_id DESC");

        // Execute The Statement

        $stmt->execute(array($item['itemID']));

        // Assign To Variable

        $comments = $stmt->fetchAll();

        ?>

        <?php foreach ($comments as $comment) { ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
                        <?php echo $comment['Member'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
        <?php } ?>
    </div>

<?php
} else {
    echo '<div class="container">';
    echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
    echo '</div>';
}
include $tpl . 'footer.php';
ob_end_flush();
?>