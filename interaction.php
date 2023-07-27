<?php
//Backend for inserting interactions for posts
require_once "realconfig.php";
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
session_start();
//validation backend
if (!isset($_SESSION['user']) || !isset($_GET["post"]) || !isset($_GET["inter"]) || !isset($_GET['page'])) {
    header("location: index.php");
} else {

    try {
        //we make sure that the user can only have one interaction per post and insert and update.
        $userCheckTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :userId = `user_id` AND :postId = `post_id`;");
        $userCheckTable->bindValue(":userId", $_SESSION['user']);
        $userCheckTable->bindValue(":postId", $_GET['post']);
        $userCheckTable->execute();
        $userCheck = $userCheckTable->fetch();

        if (empty($userCheck)) {
            $insertTable = $dbh->prepare("INSERT INTO `bi_interactions` (`user_id`, `post_id`, `interaction_type`)
            VALUES (:userId, :postId, :interaction);");
            $insertTable->bindValue(":userId",  $_SESSION['user']);
            $insertTable->bindValue(":postId", $_GET['post']);
            $insertTable->bindValue(":interaction", $_GET['inter']);
            $insertTable->execute();
        } else {
            if ($userCheck['interaction_type'] == 1 && $_GET['inter'] == 2) {
                $updateTable = $dbh->prepare("UPDATE `bi_interactions` SET `interaction_type` = 2 WHERE :userId = `user_id` AND :postId = `post_id`;");
                $updateTable->bindValue(":userId", $_SESSION['user']);
                $updateTable->bindValue(":postId", $_GET['post']);
                $updateTable->execute();
            } else if ($userCheck['interaction_type'] == 2 && $_GET['inter'] == 1) {
                $updateTable = $dbh->prepare("UPDATE `bi_interactions` SET `interaction_type` = 1 WHERE :userId = `user_id` AND :postId = `post_id`;");
                $updateTable->bindValue(":userId", $_SESSION['user']);
                $updateTable->bindValue(":postId", $_GET['post']);
                $updateTable->execute();
            }
        }

        if ($_GET['page'] == "post") {
            header("Location: post.php?id=" . htmlspecialchars($_GET['post']) . "");
        } else if ($_GET['page'] == "sublewit" && isset($_GET['sublewit'])) {
            header("Location: sublewit.php?id=" . $_GET['sublewit'] . "");
        } else if ($_GET['page'] == "comment" && isset($_GET['org'])) {
            header("Location: post.php?id=" . $_GET['org'] . "");
        } else {
            header("Location: index.php");
        }
    } catch (PDOException $e) {


        $errormessage = $e->getMessage();
        $errorcode = $e->getCode();


        echo $e;
    }
}
