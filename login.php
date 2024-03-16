<?php global $tpl;
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect to dashboard page
}
include 'init.php';
// Check if user coming from HTTP Post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['userName'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);

        // Check if the user exist in the database
        $stmt = $dbconc->prepare("SELECT userID, userName, Pass
                                FROM 
                                    users 
                                WHERE 
                                    userName = ? 
                                AND 
                                    Pass = ? ");
        $stmt->execute(array($user, $hashedPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();

        // If count > 0 this mean the database contain record about this username
        if ($count > 0) {
            $_SESSION['user'] = $user;// Register session name
            $_SESSION['uid'] = $get['userID'];
            header('Location: index.php'); // Redirect to dashboard page
            exit();
        }
    }else{
            $formErrors = array();
            $user = $_POST['userName'];
            $pass = $_POST['password'];
            $pass2 = $_POST['password2'];
            $email = $_POST['email'];
            //sanitize username
            if (isset($user)){
                $filteredUser = filter_var($user, FILTER_UNSAFE_RAW);
                if (strlen($filteredUser)  < 4) {
                    $formErrors[] = "Username can't be less than 4 characters";
                }
            }
            //confirm password
            if (isset($pass) &&  isset($pass2)){
                if (empty($pass)){
                    $formErrors[] = "Sorry password can't be empty";
                }
                if (sha1($pass) !== sha1($pass2)){
                    $formErrors[] = "Passwords do not match";
                }
            }
            //sanitize email
            if (isset($email)){
                $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (!filter_var($filteredEmail, FILTER_VALIDATE_EMAIL)){
                    $formErrors[] = "Invalid email";
                }
            }
            //Check if there is no errors
            if (empty($formErrors)){
                // check if user exists
                $check = checkItem('userName' , 'users', $user);
                if ($check == 1){
                    $formErrors[] = "Username is already taken";
                }else{
                    $stmt = $dbconc->prepare("INSERT INTO users (userName, Pass, email, RegStatus , currDate) 
                                                                VALUES (:zuser, :zpass, :zemail,0 , NOW())");
                    $stmt->execute(array('zuser' => $user, 'zpass' => sha1($pass), 'zemail' => $email));

                    $succesMsg = 'Congrats You Are Now Registered User';
                }
            }
    }
}
?>
    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> |
            <span data-class="signup">Signup</span>
        </h1>
        <!-- Start Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input
                        class="form-control"
                        type="text"
                        name="userName"
                        autocomplete="off"
                        placeholder="Username"
                        required />
            </div>
            <div class="input-container">
                <input
                        class="form-control"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Password"
                        required />
            </div>
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
        </form>
        <!-- End Login Form -->
        <!-- Start Signup Form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input
                        pattern=".{4,}"
                        title="Username Must Be Between 4 Chars"
                        class="form-control"
                        type="text"
                        name="userName"
                        autocomplete="off"
                        placeholder="Type your username"
                        required />
            </div>
            <div class="input-container">
                <input
                        minlength="4"
                        class="form-control"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Type a Complex password"
                        required />
            </div>
            <div class="input-container">
                <input
                        minlength="4"
                        class="form-control"
                        type="password"
                        name="password2"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        required />
            </div>
            <div class="input-container">
                <input
                        class="form-control"
                        type="email"
                        name="email"
                        placeholder="Type a Valid email" />
            </div>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
        </form>
        <!-- End Signup Form -->
        <div class="the-errors text-center">
            <?php
            if (!empty($formErrors)) {

                foreach ($formErrors as $error) {

                    echo '<div class="msg error">' . $error . '</div>';

                }

            }

            if (isset($succesMsg)) {

                echo '<div class="msg alert alert-success">' . $succesMsg . '</div>';

            }?>
        </div>
    </div>
<?php include $tpl . 'footer.php' ?>