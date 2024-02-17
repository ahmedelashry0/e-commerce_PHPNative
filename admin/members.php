<?php
session_start();
$pageTitle = 'MEMBERS';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        //Start Manage page
    } elseif ($do == 'Edit') { //Edit page
?>

        <h1 class="text-center">Edit Member</h1>
        <div class="container">
            <form class="form-horizontal">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" />
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
                        <input type="email" name="email" class="form-control" />
                    </div>
                </div>
                <!-- End Email Field -->
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="full" class="form-control" />
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

<?php  }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
