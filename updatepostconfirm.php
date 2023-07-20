<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {

    // $interactionTable = $dbh->prepare("DELETE FROM `bi_interactions` WHERE `post_id` = :postId;");
    // $interactionTable->bindValue(':postId', intval($_GET['id']));
    // $interactionTable->execute();

    // $sth = $dbh->prepare("DELETE FROM `bi_posts` WHERE `id` =  :postid");
    // $sth->bindValue(':postid', intval($_GET['id']));
    // $sth->execute();

    // header("Location: admin.php");
    $postTable = $dbh->prepare("UPDATE `bi_posts` SET `content` = :content, `admin_change` = 1 WHERE `id` = :id");
    $postTable->bindValue(':id', intval($_GET['id']));
    $postTable->bindValue(':content', $_POST['content-val']);
    $postTable->execute();
    //$post = $postTable->fetch();
    // echo "<form action='updatepostconfirm.php?id=".$_GET['id']."' method='post'>";
    // echo "<textarea name='content-val' id='' cols='30' rows='10'> ".$post['content']." </textarea>";
    // echo "<button type='submit'>Submit</button>";
    // echo "</form>"
    header("Location: admin.php");
    ?>


    

    <?php
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
?>