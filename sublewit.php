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
    <title>Sublewit</title>

    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

</head>
<?php
// valid id for sublewit or not
try {

    $sublewitid = $_GET['id'];

    $sth = $dbh->prepare("SELECT p.*, u.username FROM `bi_posts` p
                    JOIN `bi_users` u ON p.author_id = u.id
                    WHERE p.community_id = :id AND p.reply_id IS NULL");
    $sth->bindValue(':id', $sublewitid);
    $sth->execute();
    $sublewits = $sth->fetchAll();

    $sth2 = $dbh->prepare("SELECT * FROM bi_communities WHERE id = :commid");
    $sth2->bindValue(':commid', $sublewitid);
    $sth2->execute();
    $community = $sth2->fetch();

    $communityname = $community['name'];
    $communityinfo = $community["description"];
} catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}

?>

<body>
    <div class="container">
        <?php
        require_once "header.php";
        ?>
        <div class="sidebar">
            <h3 class="center">Top Sublewits</h3>
            <ol>
                <?php
                $sth = $dbh->prepare("SELECT c.id, c.name, COUNT(p.id) as pcount FROM `bi_communities` c 
                JOIN bi_posts p ON c.id = p.community_id 
                GROUP BY p.community_id 
                ORDER BY pcount DESC LIMIT 5;");
                $sth->execute();
                $toptensublewits = $sth->fetchAll();
                foreach ($toptensublewits as $toptensublewit) {
                    $toptensublewitid = $toptensublewit['id'];
                    echo "<li><a href = \" sublewit.php?id={$toptensublewitid}\">{$toptensublewit['name']}</a></li>";
                }
                ?>
            </ol>
        </div>
        <?php
        //display the sublewit info
        echo "<h1 class='center'><u>" .  htmlspecialchars($communityname) . "</u></h1>";
        echo "<h2 class='center'>" . htmlspecialchars($communityinfo) . "</h2>";
        if ($community['admin_change'] != NULL) {
            echo "<h4 class='center'>This sublewit has been modified by an admin</h4>";
        }

        //author of sublewit
        if ($community['user_id'] != 0) {
            $userTable = $dbh->prepare("SELECT username FROM bi_users WHERE id = :id");
            $userTable->bindValue(":id", $community['user_id']);
            $userTable->execute();
            $userName = $userTable->fetch();

            echo "<h2 class='center'><a href='profile.php?id=" . $community['user_id'] . "'>Sublewit created by <i>" . $userName['username'] . "</i></a></h2>";
        }
        ?>
        //display
        <div class="posts-container">
            <?php
            try {
                if (!empty($sublewits)) {


                    foreach ($sublewits as $post) {


                        $authorName = $dbh->prepare("SELECT * FROM `bi_users` WHERE :postAuthorId = `id`;");
                        $authorName->bindValue(':postAuthorId', $post['author_id']);
                        $authorName->execute();
                        $author = $authorName->fetch();

                        $sublewItName = $dbh->prepare("SELECT * FROM `bi_communities` WHERE :postSublewit = `id`;");
                        $sublewItName->bindValue(':postSublewit', $post['community_id']);
                        $sublewItName->execute();
                        $sublewIt = $sublewItName->fetch();

                        echo "<div class='post-div'>";

                        echo "<h2 class='post-user'><a href='profile.php?id=" . htmlspecialchars($author['id']) . "'>" . htmlspecialchars($author['username']) . "</a></h2>";
                        echo "<p class='post-sublewit'><a href='sublewit.php?id=" . htmlspecialchars($sublewIt['id']) . "'><i>" . htmlspecialchars($sublewIt['name']) . "</i></a></p>";

                        echo "<p class='post-content'>" . htmlspecialchars($post['content']) . "<a href='post.php?id=" . $post['id'] . "'> Click to see post</a></p>";


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

                            $userInteractionTable = $dbh->prepare("SELECT * FROM `bi_interactions` WHERE :postId = `post_id` AND :user = `user_id`;");
                            $userInteractionTable->bindValue(":user", $_SESSION['user']);
                            $userInteractionTable->bindValue(":postId", $post['id']);
                            $userInteractionTable->execute();
                            $userInteraction = $userInteractionTable->fetch();
                            if (!empty($userInteraction)) {
                                if ($userInteraction['interaction_type'] == 1) {
                                    echo "<div class='bottomspan green'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=1&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                
                                    <p class='post-upvotes'>Upvotes</p>
                                    
                                    <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p></a>
                                    </div>
                                    
                                    <div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=2&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                        
                                        <p class='post-downvotes'>Downvotes</p>
                                        <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p></a>
                                    </div>";
                                } else if ($userInteraction['interaction_type'] == 2) {
                                    echo "<div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=1&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                
                                    <p class='post-upvotes'>Upvotes</p>
                                    
                                    <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p></a>
                                    </div>
                                    
                                    <div class='bottomspan green'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=2&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                        
                                        <p class='post-downvotes'>Downvotes</p>
                                        <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p></a>
                                    </div>";
                                }
                            } else {
                                echo "<div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=1&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                
                                <p class='post-upvotes'>Upvotes</p>
                                
                                <p class='post-upvotes-total'>" . htmlspecialchars($upvotes) . "</p></a>
                                </div>
                                
                                <div class='bottomspan'><a href= 'interaction.php?post=" . htmlspecialchars($post['id']) . "&inter=2&page=sublewit&sublewit=" . $_GET['id'] . "'>
                                    
                                    <p class='post-downvotes'>Downvotes</p>
                                    <p class='post-downvotes-total'>" . htmlspecialchars($downvotes) . "</p></a>
                                </div>";
                            }
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
                        if ($post['admin_change'] != NULL) {
                            echo "<p><i>This post was modified by an admin</i></p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p class='center'>No posts found. Make One!</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: {$e->getMessage()}</p>";
            }
            ?>
        </div>

    </div>
</body>

</html>