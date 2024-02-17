<?php
session_start();
$pageTitle = 'MEMBERS';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        //Start Manage page
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
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" />
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="email" name="email" class="form-control " value="<?php echo $row["Email"] ?>" />
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-5">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['Fullname'] ?>" />
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
            echo 'There is no such ID';
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            $id = $_POST['userID'];
            $userName = $_POST['username'];
            $email = $_POST['email'];
            $fullName = $_POST['full'];
            $stmt = $dbconc-> prepare("UPDATE users SET userName = ? , Email = ? , Fullname = ? WHERE userID = ?");
            $stmt-> execute(array($userName, $email, $fullName, $id));
        }else {
            echo 'You are not authorized to view this page.';
        }
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
