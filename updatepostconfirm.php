<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
// Backend for updating posts

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1 || !isset($_POST['content-val'])) {
    header("location: index.php");
}

try {


    $postTable = $dbh->prepare("UPDATE `bi_posts` SET `content` = :content, `admin_change` = 1 WHERE `id` = :id");
    $postTable->bindValue(':id', intval($_GET['id']));
    $postTable->bindValue(':content', $_POST['content-val']);
    $postTable->execute();

    header("Location: admin.php");

} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
