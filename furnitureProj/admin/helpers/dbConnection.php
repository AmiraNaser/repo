<?php
session_start();
$server     = "localhost";
$dbName     = "projectDB";
$dbuser     = "root";
$dbPassword = "";

$con = mysqli_connect($server, $dbuser, $dbPassword, $dbName);
if (!$con) {
    die ('Error : '. mysqli_connect_error());
}
?>