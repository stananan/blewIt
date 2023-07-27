<?php
require "realconfig.php";
session_start();
// Backend for uploading posts

//check if input is valid
if (!isset($_SESSION['user']) || !isset($_POST['upload-val']) || !isset($_POST['sublewit-val'])) {
    header("location: index.php");
}
try {

    //insert a new post to the db
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

    $postsTable = $dbh->prepare("INSERT INTO `bi_posts` (`author_id`, `content`, `creation_time`, `reply_id`, `community_id`)
                                VALUES (:authorId, :content, NOW(), NULL, :communityId);");

    $postsTable->bindValue(':authorId', $_SESSION["user"]);
    $postsTable->bindValue(':content', $_POST['upload-val']);
    $postsTable->bindValue(':communityId', $_POST['sublewit-val']);
    $postsTable->execute();
    $_SESSION["upload-error"] = false;
} catch (PDOException $e) {
    $_SESSION["upload-error"] = true;
    echo "<p>Error: {$e->getMessage()}</p>";
}

header("Location: index.php");
exit();
