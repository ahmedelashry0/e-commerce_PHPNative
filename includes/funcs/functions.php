<?php
/*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

function getCats() {

    global $dbconc;

    $getAll = $dbconc->prepare("SELECT * FROM categories ORDER BY catID");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;}

/*
** Check If User Is Not Activated
** Function To Check The RegStatus Of The User
*/
function checkUserStatus($user) {

    global $dbconc;

    $stmtx = $dbconc->prepare("SELECT 
									userName, RegStatus 
								FROM 
									users 
								WHERE 
									userName = ? 
								AND 
									RegStatus = 0");

    $stmtx->execute(array($user));

    $status = $stmtx->rowCount();

    return $status;

}

    //Get AD items
    function getItems($where, $value , $approve = NULL) {

    global $dbconc;

    $sql = $approve == NULL ? "AND Approve = 1" : Null;

    $getItems = $dbconc->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY itemID DESC");

    $getItems->execute(array($value));

    $all = $getItems->fetchAll();

    return $all;

}


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
function redirectHome($msg, $url = null, $sec = 2){
    if ($url === null){
        $url = 'index.php';
        $link = 'Homepage';
    }else{
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
        $link = 'previous page';
    }
    echo $msg;
    echo "<div class='alert alert-info'>You will be directed to $link after $sec seconds</div>";
    header("refresh:$sec;url=$url");
    exit();
}

/*
Check if item exists
*/

function checkItem($select, $from, $value){
    global $dbconc;
    $stmt2 = $dbconc->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt2->execute(array($value));
    $count = $stmt2->rowCount();
    return $count;
}

/*
Count number of items
*/
function checkItem2($item, $from){
    global $dbconc;
    $stmt2 = $dbconc->prepare("SELECT COUNT($item) FROM $from");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

// Get items
function getLatest($select, $from, $order, $limit = 5){
    global $dbconc;
    $stmt2 = $dbconc->prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit");
    $stmt2->execute();
    $rows = $stmt2->fetchAll();
    return $rows;
}