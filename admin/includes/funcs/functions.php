<?php
/*
** Title Function that echo the page title in case the page
** has the variable $pageTitle and echo default title for other pages
*/ 

function getTitle(){
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    }else{
        echo 'Default';
    }
}

/*
Redirect func
**Error msg
** Delay seconds
*/
function redirectHome($err, $sec = 3){
    echo "<div class='alert alert-danger'>$err</div>";
    echo "<div class='alert alert-info'>You will be directed to Home page after $sec </div>";
    header("refresh:$sec;url=index.php");
}