<?php
/* 
    Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats]
*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {
    echo 'Welcome to Manage page';
    echo '<a href="?do=Add" >Add CAT + </a>';
}elseif ($do == 'Add') {
    echo 'Welcome to Add page';
}elseif ($do == 'Insert') {
    echo 'Welcome to Insert page';
}elseif ($do == 'Edit') {
    echo 'Welcome to Edit page';
}elseif ($do == 'Update') {
    echo 'Welcome to Update page';
}elseif ($do == 'Delete') {
    echo 'Welcome to Delete page';
}elseif ($do == 'Stats') {
    echo 'Welcome to Stats page';
} else {
    echo 'Error There\'s no page with this name';
}