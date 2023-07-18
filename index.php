<?php
require "realconfig.php";
session_start();

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

        <?php
        if (isset($_SESSION["user"])) {
        ?>
            <div class="upload-div">
                <form action="upload.php" method="post" class="upload-form">

                    <textarea name="upload-val" id="upload-text" cols="30" rows="5" placeholder="Text" required></textarea>
                    <!-- We will maybe change the format on how the user chooses a sublewit. Maybe text input or loop through sublewit for select -->
                    <select name="sublewit-val" id="upload-sublewit" required>
                        <?php

                        try {
                            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                            $communitiesTable = $dbh->prepare("SELECT * FROM `bi_communities`;");
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
        <?php
        }
        ?>


        <div class="posts-container">
            <!-- <div class="post-div">
                <span class="topspan">
                    <h2 class="post-user">Packersfan</h2>
                    <p class="post-sublewit"><i>Sports</i></p>
                </span>
                <span class="topspan">
                    <p class="post-content">The packers are a very cool team! <a href="post.php"> Click to see post</a></p>
                    

                </span>
                <span class="bottomspan">
                    <p class="post-upvotes">Upvotes</p>
                    <p class="post-upvotes-total">0</p>
                </span>
                <span class="bottomspan">
                    <p class="post-downvotes">Downvotes</p>
                    <p class="post-downvotes-total">0</p>
                </span>



            </div> -->

            <?php
            try {
                $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                $postsTable = $dbh->prepare("SELECT * FROM `bi_posts` ORDER BY `creation_time` DESC;");

                $postsTable->execute();

                $posts = $postsTable->fetchAll();
                foreach ($posts as $post) {
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
                    //echo "<span class='topspan'>";
                    //TODO: MAKE THE POST PAGE
                    echo "<p class='post-content'>" . $post['content'] . "<a href='post.php?id=" . $post['id'] . "'> Click to see post</a></p>";
                    //echo "</span>";
                    echo "<br>";
                    //TODO: FIGURE OUT THE BI_INTERACTIONS

                    $upvotes = 0;
                    $downvotes = 0;
                    
                    $interactionTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :postId = `post_id`;");
                    $interactionTable->bindValue(":postId", $post['id']);
                    $interactionTable->execute();
                    $interactions = $interactionTable->fetchAll();
                    foreach($interactions as $interaction){
                        if($interaction['interaction_type'] == 1){
                            $upvotes+=1;
                        }else if($interaction['interaction_type'] == 2){
                            $downvotes+=1;
                        }
                    }
                    echo "<span class='bottomspan'>
                    <p class='post-upvotes'>Upvotes</p>
                    <p class='post-upvotes-total'>".$upvotes."</p>
                    </span>
                    <span class='bottomspan'>
                        <p class='post-downvotes'>Downvotes</p>
                        <p class='post-downvotes-total'>".$downvotes."</p>
                    </span>";

                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: {$e->getMessage()}</p>";
            }
            ?>

        </div>
    </div>
</body>

</html>