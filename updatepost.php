
<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}

try {
    echo "<h1>EDIT CONTENT FOR POST #" . $_GET["id"] . "</h1>";
    $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `id` = :postId;");
    $postTable->bindValue(':postId', intval($_GET['id']));
    $postTable->execute();
    $post = $postTable->fetch();
    echo "<form action='updatepostconfirm.php?id=" . $_GET['id'] . "' method='post'>";
    echo "<textarea name='content-val' id='' cols='30' rows='10'>" . $post['content'] . "</textarea>";
    echo "<button type='submit'>Submit</button>";
    echo "</form>"
?>


    

    <?php
} catch (PDOException $e) {


    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}
    ?>