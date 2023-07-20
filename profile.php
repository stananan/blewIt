<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset(($_GET['id']))) {
    http_response_code(404);
    echo "Error 404: Page not found";
    exit();
}

try {
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
                    try {
                        $userNameTable = $dbh->prepare("SELECT `username` FROM bi_users WHERE :id = `id`");
                        $userNameTable->bindValue(":id", $_SESSION["user"]);
                        $userNameTable->execute();
                        $userName = $userNameTable->fetch();
                        if($_SESSION["admin"]==1){
                            echo "<div class='nav'><a href='admin.php'>Admin controls</a></div>";
                        }
                        echo "<div class='nav'><a href='profile.php?id=" . $_SESSION['user'] . "'>" . $userName['username'] . "</a></div>";
                        echo "<div class='nav'><a href='logout.php'>Log out</a></div>";
                        echo "<div class='nav'><a href='index.php'>Home</a></div>";
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }
                } else {
                ?>
                    <div class="nav"><a href="login.php">Login</a></div>

                    <div class="nav"><a href="register.php">Register</a></div>

                    <div class='nav'><a href='index.php'>Home</a></div>
                <?php
                }
                ?>
            </div>

        </div>
        <div class="posts-container">
            <div class="post-div">
                <?php
                try {
                    $userTable = $dbh->prepare("SELECT `creation_time`, `username`, `is_admin`, `last_login_time`, `id` FROM `bi_users` WHERE :id = `id`;");
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

                    $interactionsTable = $dbh->prepare("SELECT bi_interactions.interaction_type, bi_interactions.user_id, bi_interactions.post_id FROM `bi_interactions` JOIN `bi_posts` ON bi_posts.author_id = :userId AND bi_posts.id = bi_interactions.post_id;");
                    $interactionsTable->bindValue("userId", $user['id']);
                    $interactionsTable->execute();
                    $interactions = $interactionsTable->fetchAll();
                    $upvotes = 0;
                    $downvotes = 0;
                    foreach ($interactions as $interaction) {
                        if ($interaction['interaction_type'] == 2) {
                            $downvotes += 1;
                        } else if ($interaction['interaction_type'] == 1) {
                            $upvotes += 1;
                        }
                    }
                    echo "<h2>Bro has garnered " . $upvotes . " upvotes on his posts</h2>";
                    echo "<h2>Bro has garnered " . $downvotes . " downvotes on his posts</h2>";

                    $sublewitTable = $dbh->prepare("SELECT * FROM `bi_communities` WHERE :userId = user_id;");
                    $sublewitTable->bindValue(":userId", $user['id']);
                    $sublewitTable->execute();
                    $sublewits = $sublewitTable->fetchAll();
                    foreach ($sublewits as $sublewit) {
                        echo "<p>Bro is the founder of the " . $sublewit['name'] . " sublewit</p>";
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