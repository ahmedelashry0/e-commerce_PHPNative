<?php
// DATABASE CONNECTION
include 'connect.php';

// ROUTES
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/funcs/';
$css = 'layouts/css/';
$js = 'layouts/js/';
// INCLUDE THE IMPORTANT FILES
include $func .'functions.php';
include $lang . 'en.php';
include $tpl . 'header.php';


// INCLUDE NAVBAR ON ALL PAGES EXCEPT THE ONE WITH $noNavbar VARIABLE
if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}
