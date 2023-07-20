<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
try {
    if (!isset($_SESSION['user']) || !isset($_GET["id"])) {
        header("location: admin.php");
    }
    else{
    $sth = $dbh->prepare("DELETE FROM `bi_posts` WHERE `id` =  :postid");
    $sth->bindValue(':postid', intval($_GET['id']));
    $sth->execute();
    header("Location: admin.php");
    }
    
} 
catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
?>