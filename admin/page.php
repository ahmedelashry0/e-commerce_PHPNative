<?php
/* 
    Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats]
*/

$GO = isset($_GET['GO']) ? $_GET['GO'] : 'Manage';

if ($GO == 'Manage') {
    echo 'Welcome to Manage page';
    echo '<a href="?GO=Add" >Add CAT + </a>';
}elseif ($GO == 'Add') {
    echo 'Welcome to Add page';
}elseif ($GO == 'Insert') {
    echo 'Welcome to Insert page';
}elseif ($GO == 'Edit') {
    echo 'Welcome to Edit page';
}elseif ($GO == 'Update') {
    echo 'Welcome to Update page';
}elseif ($GO == 'Delete') {
    echo 'Welcome to Delete page';
}elseif ($GO == 'Stats') {
    echo 'Welcome to Stats page';
} else {
    echo 'Error There\'s no page with this name';
}