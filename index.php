<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blew It</title>

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

        <?php
        if (isset($_SESSION["user"])) {
            if (isset($_SESSION["upload-error"])) {
                if ($_SESSION["upload-error"] == true) echo "<h3 class='error bad'>Unsuccesful Upload</h3>";
                if ($_SESSION["upload-error"] == false) echo "<h3 class='error good'>Succesful Upload</h3>";
                unset($_SESSION['upload-error']);
            }
            if (isset($_SESSION["sublewit-error"])) {
                if ($_SESSION["sublewit-error"] == true) echo "<h3 class='error bad'>Unsuccesful Sublewit</h3>";
                if ($_SESSION["sublewit-error"] == false) echo "<h3 class='error good'>Succesful Sublewit</h3>";
                unset($_SESSION['sublewit-error']);
            }
        ?>
            <div class="create-div">

                <div class="upload-div">
                    <form action="upload.php" method="post" class="upload-form">
                        <h2>Create a Post</h2>
                        <textarea name="upload-val" id="upload-text" cols="30" rows="5" placeholder="Text" required style="resize: none;" maxlength="1024"></textarea>
                        <!-- We will maybe change the format on how the user chooses a sublewit. Maybe text input or loop through sublewit for select -->
                        <select name="sublewit-val" id="upload-sublewit">
                            <?php

                            try {

                                $communitiesTable = $dbh->prepare("SELECT * FROM `bi_communities` ORDER BY `name` ASC;");
                                $communitiesTable->execute();

                                $communitiesOptions = $communitiesTable->fetchAll();
                                foreach ($communitiesOptions as $communitiesOption) {
                                    echo "<option value='" . $communitiesOption['id'] . "'>" . $communitiesOption['name'] . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<p>Error: {$e->getMessage()}</p>";
                            }

                            ?>

                        </select>

                        <button class="upload-button" type="submit">Post</button>
                    </form>
                </div>

                <div class="sublewit-div">
                    <form action="sublewit.php" method="post" class="upload-form">
                        <h2>Create a Sublewit</h2>
                        <input type="text" name="sublewit-val" required placeholder="Genre" maxlength="20">
                        <textarea name="desc-val" id="desc-text" cols="30" rows="5" placeholder="Give a brief description" required style="resize: none;" maxlength="300"></textarea>

                        <button class="upload-button" type="submit">Create</button>
                    </form>
                </div>
            </div>

        <?php

        }

        ?>


        <div class="posts-container">

            <?php
            try {

                $postsTable = $dbh->prepare("SELECT * FROM `bi_posts` ORDER BY `creation_time` DESC;");

                $postsTable->execute();

                $posts = $postsTable->fetchAll();
                foreach ($posts as $post) {
                    if ($post['reply_id'] == NULL) {
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

                        echo "<p class='post-content'>" . $post['content'] . "<a href='post.php?id=" . $post['id'] . "'> Click to see post</a></p>";




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
                        echo "<div class='bottomspan'>
                    
                        <p class='post-upvotes'>Upvotes</p>
                        
                        <p class='post-upvotes-total'>" . $upvotes . "</p>
                    </div>
                    
                    <div class='bottomspan'>
                        <p class='post-downvotes'>Downvotes</p>
                        
                        <p class='post-downvotes-total'>" . $downvotes . "</p>
                    </div>";
                        $datetime = strtotime($post['creation_time']);
                        $formatted_date = date('m/d/Y h:i:s A', $datetime);
                        echo "<p><i>" . $formatted_date . "</i></p>";
                        if($post['admin_change'] != NULL){
                            echo "<p><i>This post was modified by an admin</i></p>";
                        }
                        echo "</div>";
                    }
                }
            } catch (PDOException $e) {
                echo "<p>Error: {$e->getMessage()}</p>";
            }
            ?>


        </div>
    </div>
</body>

</html>