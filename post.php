<?php
require "realconfig.php";
session_start();

if (!isset(($_GET['id']))) {
    http_response_code(404);
    echo "Error 404: Page not found";
    exit();
}

try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);



    $postsTableCheck = $dbh->prepare("SELECT `id` from `bi_posts`");
    $postsTableCheck->execute();
    $postsCheck = $postsTableCheck->fetchAll();
    $check = false;
    foreach ($postsCheck as $id) {

        if ($id['id'] == htmlspecialchars($_GET['id'])) {
            $check = true;
        }
    }
    if ($check == false) {
        http_response_code(404);
        echo "Error 404: Page not found";
        exit();
    }
} catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="header-div">
            <h1 class="header-title">Blew It</h1>
            <div class="header-links">
                <?php
                if (isset($_SESSION["user"])) {
                    echo "<button class='nav'><a href='profile.php?id=" . $_SESSION['user']['id'] . "'>" . $_SESSION['user']['username'] . "</a></button>";
                    echo "<button class='nav'><a href='logout.php'>Log out</a></button>";
                    echo "<button class='nav'><a href='index.php'>Home</a></button>";
                } else {
                ?>
                    <button class="nav"><a href="login.php">Login</a></button>
                    <button class="nav"><a href="register.php">Register</a></button>
                    <button class='nav'><a href='index.php'>Home</a></button>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="posts-container">
            <?php
            $postsTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :getId = `id`;");
            $postsTable->bindValue(':getId', htmlspecialchars($_GET['id']));
            $postsTable->execute();

            $post = $postsTable->fetch();

            $authorName = $dbh->prepare("SELECT * FROM `bi_users` WHERE :postAuthorId = `id`;");
            $authorName->bindValue(':postAuthorId', $post['author_id']);
            $authorName->execute();
            $author = $authorName->fetch();

            $sublewItName = $dbh->prepare("SELECT `name` FROM `bi_communities` WHERE :postSublewit = `id`;");
            $sublewItName->bindValue(':postSublewit', $post['community_id']);
            $sublewItName->execute();
            $sublewIt = $sublewItName->fetch();

            echo "<div class='post-div'>";
            echo "<span class='topspan'>";
            echo "<h2 class='post-user'><a href='profile.php?id=" . $author['id'] . "'>" . $author['username'] . "</a></h2>";
            echo "<p class='post-sublewit'><i>" . $sublewIt['name'] . "</i></p>";
            echo "</span>";
            echo "<span class='topspan'>";
            //TODO: MAKE THE POST PAGE
            echo "<p class='post-content'>" . $post['content'] . "<a href='post.php?id=" . $post['id'] . "'> Click to see post</a></p>";
            echo "</span>";
            echo "<br>";

            //TODO: FIGURE OUT THE BI_INTERACTIONS
            echo "<span class='bottomspan'>
                        <p class='post-upvotes'>Upvotes</p>
                        <p class='post-upvotes-total'>0</p>
                        </span>
                        <span class='bottomspan'>
                            <p class='post-downvotes'>Downvotes</p>
                            <p class='post-downvotes-total'>0</p>
                        </span>";

            echo "</div>";
            ?>
        </div>
    </div>

</body>

</html>