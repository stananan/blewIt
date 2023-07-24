<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {

    $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `community_id` = :communityId;");
    $postTable->bindValue(':communityId', intval($_GET['id']));
    $postTable->execute();
    $posts = $postTable->fetchAll();


    foreach ($posts as $post) {

        $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
        $interactionTable->bindValue(':postId', $post['id']);
        $interactionTable->execute();

        //scratch this idea
        //deletes all comments from his post
        // $commentsTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `reply_id` = :postId;");
        // $commentsTable->bindValue(':postId', $post['id']);
        // $commentsTable->execute();


    }

    $postTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `community_id` = :communityId;");
    $postTable->bindValue(':communityId', intval($_GET['id']));
    $postTable->execute();

    $sublewIt = $dbh->prepare("DELETE FROM `bi_communities` WHERE `community_id` = :id;");
    $sublewIt->bindValue(':id', intval($_GET['id']));
    $sublewIt->execute();



    header("Location: admin.php");
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $errormessage;
    echo $errorcode;
}
