<?php
session_start();
$pageTitle = 'MEMBERS';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        $query = (isset($_GET['page']) && $_GET['page'] == 'Pending') ? ' AND RegStatus = 0' : '';
        $stmt = $dbconc->prepare("SELECT * FROM users WHERE GroupID != 1 $query order by userID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
?>
            <h1 class="text-center">Manage Member</h1>
            <div class="container">
                <div class="  table-responsive ">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['userID'] . "</td>";
                            echo "<td>";
                            if (empty($row['avatar'])) {
                                echo 'No Image';
                            } else {
                                echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                            }
                            echo "</td>";
                            echo "<td>" . $row['userName'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['Fullname'] . "</td>";
                            echo "<td>" . $row['currDate'] . "</td>";
                            echo "<td>
                            <a href='members.php?do=Edit&ID=" . $row['userID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='members.php?do=Delete&ID=" . $row['userID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($row['RegStatus'] == 0) {
                                echo "<a href='members.php?do=Activate&ID=" . $row['userID'] . "' class='btn btn-info activate'><i class='fa fa-toggle-on'></i> Activate</a>";
                            }
                            echo "</tr>";
                        }
                        ?>

                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>;
            </div>
        <?php
        } else {
            echo "<div class='container '>";
            echo "<div class='alert alert-info'>No members found.</div>";
            echo  "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> Add New Member</a>";
            echo "</div>";
        }

        ?>
    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
                    </div>
                </div>
                <!-- End Username Field -->
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- End Password Field -->
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
                    </div>
                </div>
                <!-- End Email Field -->
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
                    </div>
                </div>
                <!-- End Full Name Field -->
                <!-- Start Avatar Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">User Avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="avatar" class="form-control" required="required" />
                    </div>
                </div>
                <!-- End Avatar Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
        <?php   } elseif ($do == 'Insert') {
        //Start Insert page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Add Member</h1>";
            echo "<div class='container'>";
            //Upload variables
            $avatarName     = $_FILES['avatar']['name'];
            $avatarSize     = $_FILES['avatar']['size'];
            $avatarTemp     = $_FILES['avatar']['tmp_name'];
            $avatarType     = $_FILES['avatar']['type'];

            //List of allowed file types
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get Avatar Extension

            $avatarNameParts = explode('.', $avatarName);
            $avatarExtension = strtolower(end($avatarNameParts));

            // Get Variables From The Form
            $userName       = $_POST['username'];
            $password       = $_POST['password'];
            $email          = $_POST['email'];
            $fullName       = $_POST['full'];
            $hashedPass     = sha1($password);
            // Validate the form
            $formErrors = array();
            if (strlen($userName) < 4) {
                $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>';
            }
            if (strlen($userName) > 20) {
                $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>';
            }
            if (empty($userName)) {
                $formErrors[] = 'Username can\'t be <strong>empty</strong>';
            }
            if (empty($password)) {
                $formErrors[] = 'Password can\'t be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'Email can\'t be <strong>empty</strong>';
            }
            if (empty($fullName)) {
                $formErrors[] = 'Full Name can\'t be <strong>empty</strong>';
            }
            if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }

            if (empty($avatarName)) {
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }

            if ($avatarSize > 4194304) {
                $formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
            }
            foreach ($formErrors as $error) {
                echo  '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (empty($formErrors)) {

                $avatar = rand(0, 1000000) . '.' . $avatarName;
                move_uploaded_file($avatarTemp, "uploads\avatars\\" . $avatar);
//                // Check if the user is already exist in DB
                $check = checkItem("userName", "users", $userName);
                if ($check == 1) {
                    $msg = "<div class ='alert alert-danger'>Username already exists</div>";
                    redirectHome($msg, 'back');
                } else {
                    $stmt = $dbconc->prepare("INSERT INTO
                                            users(userName, Pass, Email, Fullname,RegStatus, currDate, avatar)
                                            VALUES(:user, :pass, :email, :fullname, 1, now(),:avatar )");
                    $stmt->execute(array(
                        'user'      => $userName,
                        'pass'      => $hashedPass,
                        'email'     => $email,
                        'fullname'  => $fullName,
                        'avatar'    => $avatar
                    ));
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($msg, 'back');
                }
            }
        } else {
            echo "<div class ='container'>";
            $msg = "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Edit') { //Edit page
        // Check if id is numeric and get it's integer val
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $dbconc->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
        $stmt->execute(array($userID));
        $row = $stmt->fetch(); //Return data as Array
        $count = $stmt->rowCount();
        // Check if the id exists in DB
        if ($count > 0) { ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['userName'] ?>" autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Pass']; ?>" />
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leve Blank if you don't want to change" />
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="email" name="email" class="form-control " value="<?php echo $row["Email"] ?>" required="required" />
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['Fullname'] ?>" required="required" />
                        </div>
                    </div>
                    <!-- End Full Name Field -->
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
            $msg = "<div class ='alert alert-danger'>There is no such ID.</div>";
            redirectHome($msg);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            $id = $_POST['userID'];
            $userName = $_POST['username'];
            $email = $_POST['email'];
            $fullName = $_POST['full'];
            $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            // Validate the form
            $formErrors = array();
            if (strlen($userName) < 4) {
                $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>';
            }
            if (strlen($userName) > 20) {
                $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>';
            }
            if (empty($userName)) {
                $formErrors[] = 'Username can\'t be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'Email can\'t be <strong>empty</strong>';
            }
            if (empty($fullName)) {
                $formErrors[] = 'Full Name can\'t be <strong>empty</strong>';
            }
            foreach ($formErrors as $error) {
                echo  '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (empty($formErrors)) {
                $stmt2 = $dbconc->prepare("SELECT * FROM users where userName = ? AND userID != ?");
                $stmt2->execute(array($userName, $id));
                $count = $stmt2->rowCount();
                if ($count > 0) {
                    $theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

                    redirectHome($theMsg, 'back');
                } else {
                    $stmt = $dbconc->prepare("UPDATE users SET userName = ? , Email = ? , Fullname = ? , Pass= ? WHERE userID = ?");
                    $stmt->execute(array($userName, $email, $fullName, $password, $id));
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record update </div>';
                    redirectHome($msg, 'back');
                }
            }
        } else {
            echo "<div class ='container'>";
            $msg = "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg, 'back');
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
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
            redirectHome($msg, 'back');
        } else {
            $msg = "<div class ='alert alert-danger'>Member doesn\'t exist</div>";
            redirectHome($msg, 'back');
        }
        echo '</div>';
    } elseif ($do == 'Activate') {
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
            redirectHome($msg, 'back');
        } else {
            $msg = "<div class ='alert alert-danger'>Member doesn\'t exist</div>";
            redirectHome($msg);
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
