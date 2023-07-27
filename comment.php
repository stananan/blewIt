<?php
//Backend for creating comments
//I realize now that I couldve just combined this code with upload but this is simplier to read and digest
require "realconfig.php";
session_start();


//backend validation to check user
if (!isset($_SESSION['user']) || !isset($_POST['comment-val']) || !isset($_POST['sublewit-val']) || !isset($_POST['reply-val'])) {
    $_SESSION["comment-error"] = true;
    header("Location: post.php?id=" . htmlspecialchars($_POST['reply-val']) . "");
}

try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    //inserting new post into db
    $postsTable = $dbh->prepare("INSERT INTO `bi_posts` (`author_id`, `content`, `creation_time`, `reply_id`, `community_id`)
                                VALUES (:authorId, :content, NOW(), :replyId, :communityId);");

    $postsTable->bindValue(':authorId', $_SESSION["user"]);
    $postsTable->bindValue(':content', $_POST['comment-val']);
    $postsTable->bindValue(':communityId', $_POST['sublewit-val']);
    $postsTable->bindValue(':replyId', $_POST['reply-val']);
    $postsTable->execute();
    $_SESSION["comment-error"] = false;
} catch (PDOException $e) {
    $_SESSION["comment-error"] = true;
    echo "<p>Error: {$e->getMessage()}</p>";
}

header("Location: post.php?id=" . htmlspecialchars($_POST['reply-val']) . "");
exit();
