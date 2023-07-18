<?php
require "realconfig.php";
session_start();

try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

    $postsTable = $dbh->prepare("INSERT INTO `bi_posts` (`author_id`, `content`, `creation_time`, `reply_id`, `community_id`)
                                VALUES (:authorId, :content, NOW(), NULL, :communityId);");

    //TODO: replace this with the real author id later

    $postsTable->bindValue(':authorId', $_SESSION["user"]["id"]);
    $postsTable->bindValue(':content', htmlspecialchars($_POST['upload-val']));
    $postsTable->bindValue(':communityId', htmlspecialchars($_POST['sublewit-val']));
    $postsTable->execute();
} catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}

header("Location: index.php");
exit();
