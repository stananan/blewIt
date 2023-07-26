<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset(($_GET['id']))) {
    http_response_code(404);
    echo "Error 404: Page not found";
    exit();
}

//Checking valid get
try {
    $usersTableCheck = $dbh->prepare("SELECT `id` from `bi_users`");
    $usersTableCheck->execute();
    $usersCheck = $usersTableCheck->fetchAll();
    $check = false;
    foreach ($usersCheck as $id) {

        if ($id['id'] == $_GET['id']) {
            $check = true;
        }
    }
    if ($check == false) {
        http_response_code(404);
        echo "<h1 style='text-align: center;'>Error 404: Page not found</h1>";

        echo "<h1 style='text-align: center;'>This Profile does not exist or it was deleted by a moderator</h1>";
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
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

</head>

<body>
    <div class="container">
        <?php
        require_once "header.php";
        ?>

        <div class="sidebar">
            <h3 class="center">Top 10 sublewits</h3>
            <ol>
                <?php
        $sth = $dbh->prepare("SELECT c.id, c.name, COUNT(p.id) as pcount FROM `bi_communities` c 
        JOIN bi_posts p ON c.id = p.community_id 
        GROUP BY p.community_id 
        ORDER BY pcount DESC LIMIT 10;");
        $sth->execute();
        $toptensublewits = $sth->fetchAll();
        foreach ($toptensublewits as $toptensublewit){
            $toptensublewitid = $toptensublewit['id'];
            echo "<li><a href = \" sublewit.php?id={$toptensublewitid}\">{$toptensublewit['name']}</a></li>";
        }
        ?>
            </ol>
        </div>
        <!-- Profile Display -->
        <div class="posts-container">
            <div class="post-div">
                <?php
                try {
                    $userTable = $dbh->prepare("SELECT `creation_time`, `username`, `is_admin`, `last_login_time`, `id` FROM `bi_users` WHERE :id = `id`;");
                    $userTable->bindValue(":id", $_GET['id']);
                    $userTable->execute();
                    $user = $userTable->fetch();

                    echo "<h1>" . htmlspecialchars($user["username"]) . "</h1>";
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
                    echo "<h2>Bro has garnered " . $upvotes . " upvotes on his posts and comments</h2>";
                    echo "<h2>Bro has garnered " . $downvotes . " downvotes on his posts and comments</h2>";

                    $sublewitTable = $dbh->prepare("SELECT * FROM `bi_communities` WHERE :userId = user_id;");
                    $sublewitTable->bindValue(":userId", $user['id']);
                    $sublewitTable->execute();
                    $sublewits = $sublewitTable->fetchAll();
                    foreach ($sublewits as $sublewit) {
                        echo "<p>Bro is the founder of the " . htmlspecialchars($sublewit['name']) . " sublewit</p>";
                    }


                    $userPostTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :userId = `author_id`");
                    $userPostTable->bindValue(":userId", $_GET['id']);
                    $userPostTable->execute();
                    $userPosts = $userPostTable->fetchAll();

                    if (!empty($userPosts)) {
                        echo "<h2>Bro's posts</h2>";
                    }
                    foreach ($userPosts as $userPost) {
                        $datetime = strtotime($userPost['creation_time']);
                        $formatted_date = date('m/d/Y h:i:s A', $datetime);
                        if ($userPost['reply_id'] == NULL) {
                            echo "<p><a href = 'post.php?id=" . $userPost['id'] . "'>Post created on " . $formatted_date . "</a></p>";
                        } else {
                            echo "<p><a href = 'post.php?id=" . $userPost['id'] . "'>Comment created on " . $formatted_date . "</a></p>";
                        }
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