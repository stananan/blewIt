<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
// Backend for updating sublewits

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1 || !isset($_POST['content-val']) || !isset($_POST['title-val'])) {
    header("location: index.php");
}

try {


    $sublewitTable = $dbh->prepare("UPDATE `bi_communities` SET `description` = :content, `name` = :title, `admin_change` = 1 WHERE `id` = :id");
    $sublewitTable->bindValue(':id', intval($_GET['id']));
    $sublewitTable->bindValue(':content', $_POST['content-val']);
    $sublewitTable->bindValue(':title', $_POST['title-val']);

    $sublewitTable->execute();

    header("Location: admin.php");
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
