<?php
session_start();
$pageTitle = 'Comments';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        $stmt = $dbconc->prepare("SELECT comments.* , items.Name AS itemName, users.userName AS userName FROM comments
                                            INNER JOIN items
                                            ON comments.item_id = items.itemID
                                            INNER JOIN users
                                            where users.userID = comments.user_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage comments</h1>
        <div class="container">
            <div class="  table-responsive ">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item name</td>
                        <td>User name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['c_id'] . "</td>";
                        echo "<td>" . $row['comment'] . "</td>";
                        echo "<td>" . $row['itemName'] . "</td>";
                        echo "<td>" . $row['userName'] . "</td>";
                        echo "<td>" . $row['comment_date'] ."</td>";
                        echo "<td>
                            <a href='comments.php?do=Edit&comID=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&comID=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        if ($row['status'] == 0){
                            echo "<a href='comments.php?do=Approve&comID=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</tr>";
                    }
                    ?>

                </table>
    <?php } elseif ($do == 'Edit') { //Edit page
        // Check if id is numeric and get it's integer val
        $comID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;
        $stmt = $dbconc->prepare("SELECT * FROM comments WHERE c_id = ? LIMIT 1");
        $stmt->execute(array($comID));
        $row = $stmt->fetch(); //Return data as Array
        $count = $stmt->rowCount();
        // Check if the id exists in DB
        if ($count > 0) { ?>

            <h1 class="text-center">Edit comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="commentID" value="<?php echo $comID; ?>">
                    <!-- Start comment Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-5">
                            <textarea class="form-control" name="comment" ><?php echo $row['comment'] ?></textarea>
                        </div>
                    </div>
                    <!-- End comment Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>

        <?php  } else {
            echo "<div class ='container'>";
            $msg = "<div class ='alert alert-danger'>There is no such comment.</div>";
            redirectHome($msg);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update comment</h1>";
            echo "<div class='container'>";
            $id = $_POST['commentID'];
            $comment = $_POST['comment'];
                $stmt = $dbconc->prepare("UPDATE comments SET comment = ?   WHERE c_id = ?");
                $stmt->execute(array( $comment, $id));
                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record update </div>';
                redirectHome($msg, 'back');
        } else {
            echo "<div class ='container'>";
            $msg= "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg , 'back');
            echo "</div>";
        }
        echo "</div>";
    }elseif ($do == 'Delete'){
        echo '<h1 class="text-center">Delete comment</h1>';
        echo    '<div class="container">';
        // Check if id is numeric and get it's integer val
        $commentID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;
        $check = checkItem('c_id', 'comments', $commentID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("DELETE FROM comments WHERE c_id = :comID Limit 1;");
            $stmt->bindParam(":comID", $commentID);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Comment doesn\'t exist</div>";
            redirectHome($msg );
        }
        echo '</div>';
    }elseif ($do == 'Approve'){
        echo '<h1 class="text-center">Approve comment</h1>';
        echo '<div class="container">';
        // Check if id is numeric and get it's integer val
        $commentID = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;
        $check = checkItem('c_id', 'comments', $commentID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("UPDATE comments SET status = 1 WHERE c_id = ? Limit 1;");
            $stmt->execute(array($commentID));
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Comment doesn\'t exist</div>";
            redirectHome($msg );
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
