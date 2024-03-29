<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';
if (isset($_SESSION['Username'])) {
    header('Location: dashboard.php'); // Redirect to dashboard page
}
include 'init.php';

// Check if user coming from HTTP Post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // Check if the user exist in the database
    $stmt = $dbconc->prepare("SELECT userID, userName, Pass
                                FROM 
                                    users 
                                WHERE 
                                    userName = ? 
                                AND 
                                    Pass = ? 
                                AND 
                                    GroupID = 1
                                    LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch(); //Return data as Array
    $count = $stmt->rowCount();

    // If count > 0 this mean the database contain record about this username
    if ($count > 0) {
        $_SESSION['Username'] = $username; // Register session name
        $_SESSION['ID'] = $row['userID'];
        header('Location: dashboard.php'); // Redirect to dashboard page
        exit();
    }
}

?>


<form class="login" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login">
</form>
<?php include $tpl . 'footer.php'; ?>