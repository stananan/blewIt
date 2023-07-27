<?php
// Backend for deleting posts
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

// backend validation to check if user is a admin

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {
    //deleting the post from the db
    $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
    $interactionTable->bindValue(':postId', intval($_GET['id']));
    $interactionTable->execute();

    $sth = $dbh->prepare("DELETE FROM `bi_posts` WHERE `id` =  :postid");
    $sth->bindValue(':postid', intval($_GET['id']));
    $sth->execute();

    header("Location: admin.php");
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
