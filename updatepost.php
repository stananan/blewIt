<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
//Frontend for upddating posts

//checking if the user is admin
if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}
// checking if there is a post
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

</head>

<body>


    <div class="container">
        <?php
        require_once "header.php";
        ?>
        <div class="posts-container">
            <!-- page for updating cotent of post -->
            <div class="post-div">
                <?php
                try {

                    echo "<h1>EDIT CONTENT FOR POST #" . htmlspecialchars($_GET["id"]) . "</h1>";
                    echo "<form action='updatepostconfirm.php?id=" . htmlspecialchars($_GET['id']) . "' method='post'>";
                    echo "<h2>Content</h2>";
                    echo "<textarea name='content-val' cols='30' rows='10' required style='resize: none;' maxlength='1024'>" . htmlspecialchars($post['content']) . "</textarea>";
                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";
                } catch (PDOException $e) {


                    $errormessage = $e->getMessage();
                    $errorcode = $e->getCode();


                    echo $e;
                }
                ?>
            </div>
        </div>
    </div>


</body>

</html>
<?php
