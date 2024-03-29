<?php

// Error Reporting

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_DEPRECATED);

$sessionUser = isset($_SESSION['user']) ? $_SESSION['user'] : '';

// DATABASE CONNECTION
include 'admin/connect.php';

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



