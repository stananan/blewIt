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
        echo "<h1 style='text-align: center;'>Error 404: Page not found</h1>";

        echo "<h1 style='text-align: center;'>This Post does not exist or it was deleted by a moderator</h1>";
        exit();
    }
    $postsTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :getId = `id`;");
    $postsTable->bindValue(':getId', htmlspecialchars($_GET['id']));
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
                    try {
                        $userNameTable = $dbh->prepare("SELECT `username` FROM bi_users WHERE :id = `id`");
                        $userNameTable->bindValue(":id", $_SESSION["user"]);
                        $userNameTable->execute();
                        $userName = $userNameTable->fetch();
                        if ($_SESSION["admin"] == 1) {
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
            <?php
            try {



                $authorName = $dbh->prepare("SELECT * FROM `bi_users` WHERE :postAuthorId = `id`;");
                $authorName->bindValue(':postAuthorId', $post['author_id']);
                $authorName->execute();
                $author = $authorName->fetch();

                $sublewItName = $dbh->prepare("SELECT `name` FROM `bi_communities` WHERE :postSublewit = `id`;");
                $sublewItName->bindValue(':postSublewit', $post['community_id']);
                $sublewItName->execute();
                $sublewIt = $sublewItName->fetch();

                echo "<div class='post-div'>";

                echo "<h2 class='post-user'><a href='profile.php?id=" . $author['id'] . "'>" . $author['username'] . "</a></h2>";
                echo "<p class='post-sublewit'><i>" . $sublewIt['name'] . "</i></p>";


                //TODO: MAKE THE POST PAGE
                echo "<p class='post-content'>" . $post['content'] . "</p>";



                //TODO: FIGURE OUT THE BI_INTERACTIONS
                $upvotes = 0;
                $downvotes = 0;

                $interactionTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :postId = `post_id`;");
                $interactionTable->bindValue(":postId", $post['id']);
                $interactionTable->execute();
                $interactions = $interactionTable->fetchAll();
                foreach ($interactions as $interaction) {
                    if ($interaction['interaction_type'] == 1) {
                        $upvotes += 1;
                    } else if ($interaction['interaction_type'] == 2) {
                        $downvotes += 1;
                    }
                }
                if (isset($_SESSION['user'])) {

                    echo "<div class='bottomspan'><a href= 'interaction.php?post=" . $post['id'] . "&inter=1'>
                    
                <p class='post-upvotes'>Upvotes</p>
                
                <p class='post-upvotes-total'>" . $upvotes . "</p></a>
                </div>
                
                <div class='bottomspan'><a href= 'interaction.php?post=" . $post['id'] . "&inter=2'>
                    
                    <p class='post-downvotes'>Downvotes</p>
                    <p class='post-downvotes-total'>" . $downvotes . "</p></a>
                </div>";
                } else {
                    echo "<div class='bottomspan'>
                    
                    <p class='post-upvotes'>Upvotes</p>
                    
                    <p class='post-upvotes-total'>" . $upvotes . "</p>
                </div>
                
                <div class='bottomspan'>
                    <p class='post-downvotes'>Downvotes</p>
                    
                    <p class='post-downvotes-total'>" . $downvotes . "</p>
                </div>";
                }
                $datetime = strtotime($post['creation_time']);
                $formatted_date = date('m/d/Y h:i:s A', $datetime);
                echo "<p><i>" . $formatted_date . "</i></p>";
                if ($post['reply_id'] != NULL) {
                    echo "<p>This post is a comment to <a href='post.php?id=" . $post['reply_id'] . "'><i>this post</i></a></p>";
                }

                echo "</div>";
            } catch (PDOException $e) {
                echo "<p>Error: {$e->getMessage()}</p>";
            }


            ?>

            <!-- COMMENTS -->


            <div class="post-div">
                <h3>Comments</h3>
                <?php
                if (isset($_SESSION["user"])) {

                    if (isset($_SESSION["comment-error"])) {
                        if ($_SESSION["comment-error"] == true) echo "<h3 class='error bad'>Unsuccesful Comment</h3>";
                        if ($_SESSION["comment-error"] == false) echo "<h3 class='error good'>Succesful Comment</h3>";
                        unset($_SESSION['comment-error']);
                    }
                ?>

                    <form action="comment.php" method="post">

                        <textarea name="comment-val" id="comment-text" cols="30" rows="5" placeholder="Text" required style="resize: none;" maxlength="1024"></textarea>

                        <input type="radio" name="sublewit-val" value='<?php echo $post['community_id']; ?>' checked style="display:none;">
                        <input type="radio" name="reply-val" value='<?php echo $post['id']; ?>' checked style="display:none;">
                        <button type="submit">Comment</button>


                    </form>

                <?php

                }

                $commentsTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :postId = reply_id ORDER BY `creation_time` DESC;");
                $commentsTable->bindValue(":postId", $post['id']);
                $commentsTable->execute();
                $comments = $commentsTable->fetchAll();

                foreach ($comments as $comment) {
                    $authorName = $dbh->prepare("SELECT * FROM `bi_users` WHERE :postAuthorId = `id`;");
                    $authorName->bindValue(':postAuthorId', $comment['author_id']);
                    $authorName->execute();
                    $author = $authorName->fetch();

                    $sublewItName = $dbh->prepare("SELECT `name` FROM `bi_communities` WHERE :postSublewit = `id`;");
                    $sublewItName->bindValue(':postSublewit', $comment['community_id']);
                    $sublewItName->execute();
                    $sublewIt = $sublewItName->fetch();

                    echo "<div class='post-div'>";

                    echo "<h2 class='post-user'><a href='profile.php?id=" . $author['id'] . "'>" . $author['username'] . "</a></h2>";
                    echo "<p class='post-sublewit'><i>" . $sublewIt['name'] . "</i></p>";

                    echo "<p class='post-content'>" . $comment['content'] . "<a href='post.php?id=" . $comment['id'] . "'> Click to see comment</a></p>";


                    $upvotes = 0;
                    $downvotes = 0;

                    $interactionTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :postId = `post_id`;");
                    $interactionTable->bindValue(":postId", $comment['id']);
                    $interactionTable->execute();
                    $interactions = $interactionTable->fetchAll();
                    foreach ($interactions as $interaction) {
                        if ($interaction['interaction_type'] == 1) {
                            $upvotes += 1;
                        } else if ($interaction['interaction_type'] == 2) {
                            $downvotes += 1;
                        }
                    }
                    echo "<div class='bottomspan'>
                    
                        <p class='post-upvotes'>Upvotes</p>
                        
                        <p class='post-upvotes-total'>" . $upvotes . "</p>
                    </div>
                    
                    <div class='bottomspan'>
                        <p class='post-downvotes'>Downvotes</p>
                        
                        <p class='post-downvotes-total'>" . $downvotes . "</p>
                    </div>";
                    $datetime = strtotime($comment['creation_time']);
                    $formatted_date = date('m/d/Y h:i:s A', $datetime);
                    echo "<p><i>" . $formatted_date . "</i></p>";

                    echo "</div>";
                }
                ?>

            </div>










        </div>
    </div>

</body>

</html>