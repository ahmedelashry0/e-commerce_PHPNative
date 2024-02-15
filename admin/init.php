<?php
// DATABASE CONNECTION
include 'connect.php';

// ROUTES
$tpl = 'includes/templates/';
$css = 'layouts/css/';
$js = 'layouts/js/';
$lang = 'includes/languages/';
// INCLUDE THE IMPORTANT FILES
include $lang . 'en.php';
include $tpl . 'header.php';


// INCLUDE NAVBAR ON ALL PAGES EXCEPT THE ONE WITH $noNavbar VARIABLE
if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}
