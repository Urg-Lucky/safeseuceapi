<?php
$server  = 'localhost';
$dbuser = 'savefutu';
$dbpass = 'Udit@1234566$';
$dbname = 'savefutu_safe_db';
$con = new mysqli($server, $dbuser, $dbpass, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
