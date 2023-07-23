
<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1 || isset($post['content'])) {
    header("location: index.php");
}
try {

    $postsTableCheck = $dbh->prepare("SELECT `id` from `bi_posts`");
    $postsTableCheck->execute();
    $postsCheck = $postsTableCheck->fetchAll();
    $check = false;
    foreach ($postsCheck as $id) {

        if ($id['id'] == $_GET['id']) {
            $check = true;
        }
    }
    if ($check == false) {
        http_response_code(404);
        echo "<h1 style='text-align: center;'>Error 404: Page not found</h1>";

        echo "<h1 style='text-align: center;'>This Post does not exist or it was deleted by a moderator</h1>";
        exit();
    }
    $postsTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :getId = `id`;");
    $postsTable->bindValue(':getId', $_GET['id']);
    $postsTable->execute();

    $post = $postsTable->fetch();
} catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}

try {
    echo "<h1>EDIT CONTENT FOR POST #" . htmlspecialchars($_GET["id"]) . "</h1>";
    $postTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE `id` = :postId;");
    $postTable->bindValue(':postId', intval($_GET['id']));
    $postTable->execute();
    $post = $postTable->fetch();
    echo "<form action='updatepostconfirm.php?id=" . htmlspecialchars($_GET['id']) . "' method='post'>";
    echo "<textarea name='content-val' id='' cols='30' rows='10'>" . htmlspecialchars($post['content']) . "</textarea>";
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