<?php

$dsn = 'mysql:dbname=dota2;host=localhost;port=3306';
$username = 'root';
$password = '';
try {
    $db = new PDO($dsn, $username, $password); // also allows an extra parameter of configuration
} catch(PDOException $e) {
    die('Could not connect to the database:<br/>' . $e);
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $db->prepare("SELECT * FROM `items` WHERE `side_shop` = :side");
// $statement->bindValue(':side','1');
$statement->execute(array(':side'=>'1'));

$items = $statement->FetchAll();
//echo $db->exec($statement);
//echo $foods->rowCount();
foreach($items as $item) {
    echo $item['localized_name'] . '<br />';
}