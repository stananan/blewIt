<?php
require_once "realconfig.php";
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
session_start();

if (!isset($_SESSION['user']) || !isset($_GET["post"]) || !isset($_GET["inter"])) {
    header("location: index.php");
} else {

    try {
        $userCheckTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :userId = `user_id` AND :postId = `post_id`;");
        $userCheckTable->bindValue(":userId", $_SESSION['user']);
        $userCheckTable->bindValue(":postId", htmlspecialchars($_GET['post']));
        $userCheckTable->execute();
        $userCheck = $userCheckTable->fetch();

        if (empty($userCheck)) {
            $insertTable = $dbh->prepare("INSERT INTO `bi_interactions` (`user_id`, `post_id`, `interaction_type`)
            VALUES (:userId, :postId, :interaction);");
            $insertTable->bindValue(":userId",  $_SESSION['user']);
            $insertTable->bindValue(":postId", htmlspecialchars($_GET['post']));
            $insertTable->bindValue(":interaction", htmlspecialchars($_GET['inter']));
            $insertTable->execute();
        } else {
            if ($userCheck['interaction_type'] == 1 && htmlspecialchars($_GET['inter']) == 2) {
                $updateTable = $dbh->prepare("UPDATE `bi_interactions` SET `interaction_type` = 2 WHERE :userId = `user_id` AND :postId = `post_id`;");
                $updateTable->bindValue(":userId", $_SESSION['user']);
                $updateTable->bindValue(":postId", htmlspecialchars($_GET['post']));
                $updateTable->execute();
            } else if ($userCheck['interaction_type'] == 2 && htmlspecialchars($_GET['inter']) == 1) {
                $updateTable = $dbh->prepare("UPDATE `bi_interactions` SET `interaction_type` = 1 WHERE :userId = `user_id` AND :postId = `post_id`;");
                $updateTable->bindValue(":userId", $_SESSION['user']);
                $updateTable->bindValue(":postId", htmlspecialchars($_GET['post']));
                $updateTable->execute();
            }
        }
        header("Location: post.php?id=" . htmlspecialchars($_GET['post']) . "");
    } catch (PDOException $e) {


        $errormessage = $e->getMessage();
        $errorcode = $e->getCode();


        echo $e;
    }
}