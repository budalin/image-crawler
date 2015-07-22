<?php

$dsn = 'mysql:dbname=dota2;host=localhost;port=3306';
$username = 'root';
$password = '';
try {
    $db = new PDO($dsn, $username, $password); // also allows an extra parameter of configuration
} catch(PDOException $e) {
    die('Could not connect to the database:<br/>' . $e);
}
