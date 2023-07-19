<?php
require "realconfig.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
}
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $sublewitTable = $dbh->prepare("INSERT INTO `bi_communities` (`user_id`, `name`, `description`) VALUES (:userId, :name, :desc);");
    $sublewitTable->bindValue(":userId", $_SESSION['user']);
    $sublewitTable->bindValue(":name", htmlspecialchars($_POST['sublewit-val']));
    $sublewitTable->bindValue(":desc", htmlspecialchars($_POST['desc-val']));
    $sublewitTable->execute();
    $_SESSION["sublewit-error"] = false;
} catch (PDOException $e) {
    $_SESSION["sublewit-error"] = true;
    echo "<p>Error: {$e->getMessage()}</p>";
}

header("Location: index.php");
exit();
