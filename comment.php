<?php

// var_dump($_POST['comment-val']);
// var_dump($_SESSION['user']);

// print_r($_POST);


require "realconfig.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
}
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

    $postsTable = $dbh->prepare("INSERT INTO `bi_posts` (`author_id`, `content`, `creation_time`, `reply_id`, `community_id`)
                                VALUES (:authorId, :content, NOW(), :replyId, :communityId);");

    $postsTable->bindValue(':authorId', $_SESSION["user"]);
    $postsTable->bindValue(':content', htmlspecialchars($_POST['comment-val']));
    $postsTable->bindValue(':communityId', htmlspecialchars($_POST['sublewit-val']));
    $postsTable->bindValue(':replyId', htmlspecialchars($_POST['reply-val']));
    $postsTable->execute();
    $_SESSION["comment-error"] = false;
} catch (PDOException $e) {
    $_SESSION["comment-error"] = true;
    echo "<p>Error: {$e->getMessage()}</p>";
}

header("Location: post.php?id=" . htmlspecialchars($_POST['reply-val']) . "");
exit();