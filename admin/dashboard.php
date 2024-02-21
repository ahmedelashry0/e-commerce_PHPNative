<?php
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php'; ?>
    <div class="container home-stats text-center">
        <h1> Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    Total Members
                    <span><a href="members.php"> <?php echo checkItem2('userID', 'users') ?></a></span></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    Pending Members
                    <span><a href="members.php?do=Manage&page=Pending">25</a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    Total Items
                    <span>200</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    Total Comments
                    <span>200</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Latest Registered Users
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        Test
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest Items
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        Test
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    include $tpl . 'footer.php';
} else {
    header('Location: index.php'); // Redirect to dashboard page
    exit();
}
