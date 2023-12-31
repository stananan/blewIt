<?php
//Backend for deleting sublewits
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

//backend validation

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {

    //looping through posts, and then delete all interactions on those posts that are in the sublewit
    $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `community_id` = :communityId;");
    $postTable->bindValue(':communityId', intval($_GET['id']));
    $postTable->execute();
    $posts = $postTable->fetchAll();


    foreach ($posts as $post) {

        $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
        $interactionTable->bindValue(':postId', $post['id']);
        $interactionTable->execute();
    }

    //deleting the posts

    $postTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `community_id` = :communityId;");
    $postTable->bindValue(':communityId', intval($_GET['id']));
    $postTable->execute();

    //deleting the sublewit

    $sublewIt = $dbh->prepare("DELETE FROM `bi_communities` WHERE `id` = :id;");
    $sublewIt->bindValue(':id', intval($_GET['id']));
    $sublewIt->execute();



    header("Location: admin.php");
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $errormessage;
    echo $errorcode;
}
