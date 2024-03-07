<?php
$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = 'sama1072005';
$OPTIONS = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $dbconc = new PDO($dsn, $user, $pass, $OPTIONS);
    $dbconc->SETAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Failed to connect' . $e->getMessage();
}
