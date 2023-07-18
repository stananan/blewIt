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



    $usersTableCheck = $dbh->prepare("SELECT `id` from `bi_users`");
    $usersTableCheck->execute();
    $usersCheck = $usersTableCheck->fetchAll();
    $check = false;
    foreach ($usersCheck as $id) {

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
    <title>Profile</title>

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
            <div class="post-div">
                <?php
                $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                try {
                    $userTable = $dbh->prepare("SELECT * FROM `bi_users` WHERE :id = `id`;");
                    $userTable->bindValue(":id", $_GET['id']);
                    $userTable->execute();
                    $user = $userTable->fetch();
                    echo "<h1>" . $user["username"] . "</h1>";
                    if ($user["is_admin"] == 0) {
                        echo "<p>Bro is not an admin</p>";
                    } else {
                        echo "<p>Bro is a admin</p>";
                    }
                    $datetime = strtotime($user["creation_time"]);
                    $formatted_date = date('m/d/Y h:i:s A', $datetime);
                    echo "<p>Bro made his account on " . $formatted_date;
                    $datetime = strtotime($user["last_login_time"]);
                    $formatted_date = date('m/d/Y h:i:s A', $datetime);
                    echo "<p>Bro last logged in on " . $formatted_date;


                    echo "<h2>Bro's posts</h2>";
                    $userPostTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :userId = `author_id`");
                    $userPostTable->bindValue(":userId", $_GET['id']);
                    $userPostTable->execute();
                    $userPosts = $userPostTable->fetchAll();

                    foreach ($userPosts as $userPost) {
                        $datetime = strtotime($userPost['creation_time']);
                        $formatted_date = date('m/d/Y h:i:s A', $datetime);
                        echo "<p><a href = 'post.php?id=" . $userPost['id'] . "'>Post created on " . $formatted_date . "</a></p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error: {$e->getMessage()}</p>";
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>