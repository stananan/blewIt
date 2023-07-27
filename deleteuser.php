<?php
//backend for deleting users
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (isset($_SESSION['user']) && isset($_GET["id"]) && $_GET["id"] == "self") {
    try {

        $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `author_id` = :userid;");
        $postTable->bindValue(':userid', $_SESSION['user']);
        $postTable->execute();
        $posts = $postTable->fetchAll();


        foreach ($posts as $post) {
            $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
            $interactionTable->bindValue(':postId', $post['id']);
            $interactionTable->execute();
        }

        $postTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `author_id` = :userid;");
        $postTable->bindValue(':userid', $_SESSION['user']);
        $postTable->execute();

        $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `user_id` = :userId;");
        $interactionTable->bindValue(':userId', $_SESSION['user']);
        $interactionTable->execute();


        $userTable = $dbh->prepare("DELETE FROM `bi_users` WHERE `id` =  :userid;");
        $userTable->bindValue(':userid', $_SESSION['user']);
        $userTable->execute();



        header("Location: logout.php");
        die();
    } catch (PDOException $e) {


        $errormessage = $e->getMessage();
        $errorcode = $e->getCode();


        echo $errormessage;
        echo $errorcode;
    }
}

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {

    $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `author_id` = :userid;");
    $postTable->bindValue(':userid', intval($_GET['id']));
    $postTable->execute();
    $posts = $postTable->fetchAll();


    foreach ($posts as $post) {
        //delete interactions on his posts
        $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
        $interactionTable->bindValue(':postId', $post['id']);
        $interactionTable->execute();

        //scratch this idea
        //deletes all comments from his post
        // $commentsTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `reply_id` = :postId;");
        // $commentsTable->bindValue(':postId', $post['id']);
        // $commentsTable->execute();


    }

    $postTable = $dbh->prepare("DELETE FROM `bi_posts` WHERE `author_id` = :userid;");
    $postTable->bindValue(':userid', intval($_GET['id']));
    $postTable->execute();

    $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `user_id` = :userId;");
    $interactionTable->bindValue(':userId', intval($_GET['id']));
    $interactionTable->execute();


    $userTable = $dbh->prepare("DELETE FROM `bi_users` WHERE `id` =  :userid;");
    $userTable->bindValue(':userid', intval($_GET['id']));
    $userTable->execute();



    header("Location: admin.php");
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $errormessage;
    echo $errorcode;
}
