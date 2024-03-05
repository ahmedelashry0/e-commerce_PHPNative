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
                            <input type="text" name="comment" class="form-control" value="<?php echo $row['comment'] ?>"" />
                        </div>
                    </div>
                    <!-- End comment Field -->
                   <!-- Start Items Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Items</label>
                        <div class="col-sm-10 col-md-6">
                            <select name = "items">
                                <?php
                                $stmt = $dbconc->prepare("SELECT * FROM items");
                                $stmt->execute();
                                $items = $stmt->fetchAll();
                                foreach ($items as $item){
                                    echo "<option value='" . $item['itemID'] . "'" . ($row['item_id'] == $item['itemID'] ? ' selected' : '') . ">" . $item['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Items Field -->
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <select name = "users">
                                <?php
                                $stmt = $dbconc->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user){
                                    echo "<option value='" . $user['userID'] . "'" . ($row['user_id'] == $user['userID'] ? ' selected' : '') . ">" . $user['userName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
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
            $item_id = $_POST['items'];
            $user_id = $_POST['users'];
//            // Validate the form
//            $formErrors = array();
//            if (strlen($userName) < 4) {
//                $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>';
//            }
//            if (strlen($userName) > 20) {
//                $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>';
//            }
//            if (empty($userName)) {
//                $formErrors[] = 'Username can\'t be <strong>empty</strong>';
//            }
//            if (empty($email)) {
//                $formErrors[] = 'Email can\'t be <strong>empty</strong>';
//            }
//            if (empty($fullName)) {
//                $formErrors[] = 'Full Name can\'t be <strong>empty</strong>';
//            }
//            foreach ($formErrors as $error) {
//                echo  '<div class="alert alert-danger">' . $error . '</div>';
//            }
//            if (empty($formErrors)) {
                $stmt = $dbconc->prepare("UPDATE comments SET comment = ? , item_id = ? , user_id = ?  WHERE c_id = ?");
                $stmt->execute(array( $comment, $item_id, $user_id, $id));
                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record update </div>';
                redirectHome($msg, 'back');
//            }
        } else {
            echo "<div class ='container'>";
            $msg= "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg , 'back');
            echo "</div>";
        }
        echo "</div>";
    }elseif ($do == 'Delete'){
        echo '<h1 class="text-center">Delete Member</h1>';
        echo    '<div class="container">';
        // Check if id is numeric and get it's integer val
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $check = checkItem('userID', 'users', $userID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("DELETE FROM users WHERE userID = :userID Limit 1;");
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Member doesn\'t exist</div>";
            redirectHome($msg );
        }
        echo '</div>';
    }elseif ($do == 'Activate'){
        echo '<h1 class="text-center">Activate Member</h1>';
        echo '<div class="container">';
        // Check if id is numeric and get it's integer val
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $check = checkItem('userID', 'users', $userID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("UPDATE users SET RegStatus = 1 WHERE userID = ? Limit 1;");
            $stmt->execute(array($userID));
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Member doesn\'t exist</div>";
            redirectHome($msg );
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
