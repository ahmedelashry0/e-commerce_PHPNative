<?php
session_start();
$pageTitle = 'MEMBERS';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        $stmt = $dbconc->prepare("SELECT * FROM users WHERE GroupID != 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>
        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="  table-responsive ">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
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
                        echo "<td>" . $row['userName'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['Fullname'] . "</td>";
                        echo "<td>" . "</td>";
                        echo "<td>
                            <a href='members.php?do=Edit&ID=" . $row['userID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='members.php?do=Delete&ID=" . $row['userID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>;
        </div>
    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
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
            foreach ($formErrors as $error) {
                echo  '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (empty($formErrors)) {
                // Check if the user is already exist in DB
                $stmt = $dbconc->prepare("INSERT INTO 
                                            users(userName, Pass, Email, Fullname)
                                            VALUES(:user, :pass, :email, :fullname)");
                $stmt->execute(array(
                    'user'      => $userName,
                    'pass'      => $hashedPass,
                    'email'     => $email,
                    'fullname'  => $fullName
                ));
                echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
            }
        } else {
            $msg = 'You are not authorized to view this page.';
            redirectHome($msg);
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
            $msg = 'There is no such ID';
            redirectHome($msg);
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
                $stmt = $dbconc->prepare("UPDATE users SET userName = ? , Email = ? , Fullname = ? , Pass= ? WHERE userID = ?");
                $stmt->execute(array($userName, $email, $fullName, $password, $id));
                echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record update </div>';
            }
        } else {
            $msg= 'You are not authorized to view this page.';
            redirectHome($msg, 6);
        }
        echo "</div>";
    }elseif ($do == 'Delete'){
        echo '<h1 class="text-center">Delete Member</h1>';
        echo    '<div class="container">';
        // Check if id is numeric and get it's integer val
        $userID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $dbconc->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
        $stmt->execute(array($userID));
        $count = $stmt->rowCount();
        // Check if the id exists in DB
        if ($count > 0) {
            $stmt = $dbconc->prepare("DELETE FROM users WHERE userID = :userID Limit 1;");
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';
        }else{
            $msg= 'Member doesn\'t exist';
            redirectHome($msg);
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
