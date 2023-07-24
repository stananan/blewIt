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
    <title>Post</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <?php
        require_once "header.php";
        ?>
        <div class="posts-container">
            <?php
            try {



                $authorName = $dbh->prepare("SELECT * FROM `bi_users` WHERE :postAuthorId = `id`;");
                $authorName->bindValue(':postAuthorId', $post['author_id']);
                $authorName->execute();
                $author = $authorName->fetch();

                $sublewItName = $dbh->prepare("SELECT `name` FROM `bi_communities` WHERE :postSublewit = `community_id`;");
                $sublewItName->bindValue(':postSublewit', $post['community_id']);
                $sublewItName->execute();
                $sublewIt = $sublewItName->fetch();

                echo "<div class='post-div'>";

                echo "<h2 class='post-user'><a href='profile.php?id=" . htmlspecialchars($author['id']) . "'>" . htmlspecialchars($author['username']) . "</a></h2>";
                echo "<p class='post-sublewit'><i>" . htmlspecialchars($sublewIt['name']) . "</i></p>";


                //TODO: MAKE THE POST PAGE
                echo "<p class='post-content'>" . htmlspecialchars($post['content']) . "</p>";



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

                    echo "<div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=1'>
                    
                <p class='post-upvotes'>Upvotes</p>
                
                <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p></a>
                </div>
                
                <div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=2'>
                    
                    <p class='post-downvotes'>Downvotes</p>
                    <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p></a>
                </div>";
                } else {
                    echo "<div class='bottomspan'>
                    
                    <p class='post-upvotes'>Upvotes</p>
                    
                    <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p>
                </div>
                
                <div class='bottomspan'>
                    <p class='post-downvotes'>Downvotes</p>
                    
                    <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p>
                </div>";
                }
                $datetime = strtotime($post['creation_time']);
                $formatted_date = date('m/d/Y h:i:s A', $datetime);
                echo "<p><i>" . htmlspecialchars($formatted_date) . "</i></p>";

                if ($post['reply_id'] != NULL) {
                    $postCommentTable = $dbh->prepare("SELECT * FROM `bi_posts` WHERE :id = `id`");
                    $postCommentTable->bindValue(":id", $post['reply_id']);
                    $postCommentTable->execute();
                    $postComment = $postCommentTable->fetch();

                    if ($postComment['reply_id'] != NULL) {
                        echo "<p>This comment is a comment to <a href='post.php?id=" . htmlspecialchars($post['reply_id']) . "'><i>this comment</i></a></p>";
                    } else if ($postComment['reply_id'] == NULL) {
                        echo "<p>This comment is a comment to <a href='post.php?id=" . htmlspecialchars($post['reply_id']) . "'><i>this post</i></a></p>";
                    }
                }
                if ($post['admin_change'] != NULL) {
                    echo "<p><i>This post was modified by an admin</i></p>";
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

                    $sublewItName = $dbh->prepare("SELECT `name` FROM `bi_communities` WHERE :postSublewit = `community_id`;");
                    $sublewItName->bindValue(':postSublewit', $comment['community_id']);
                    $sublewItName->execute();
                    $sublewIt = $sublewItName->fetch();

                    echo "<div class='post-div'>";

                    echo "<h2 class='post-user'><a href='profile.php?id=" . htmlspecialchars($author['id']) . "'>" . htmlspecialchars($author['username']) . "</a></h2>";
                    echo "<p class='post-sublewit'><i>" . htmlspecialchars($sublewIt['name']) . "</i></p>";

                    echo "<p class='post-content'>" . htmlspecialchars($comment['content']) . "<a href='post.php?id=" . htmlspecialchars($comment['id']) . "'> Click to see comment</a></p>";


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
                        
                        <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p>
                    </div>
                    
                    <div class='bottomspan'>
                        <p class='post-downvotes'>Downvotes</p>
                        
                        <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p>
                    </div>";
                    $datetime = strtotime($comment['creation_time']);
                    $formatted_date = date('m/d/Y h:i:s A', $datetime);
                    echo "<p><i>" . htmlspecialchars($formatted_date) . "</i></p>";
                    if ($comment['admin_change'] != NULL) {
                        echo "<p><i>This post was modified by an admin</i></p>";
                    }

                    echo "</div>";
                }
                ?>

            </div>










        </div>
    </div>

</body>

</html>