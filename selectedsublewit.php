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
</head>
<?php

                try{
                    
                    $sublewitid = $_GET['id'];
                    
                    $sth = $dbh->prepare("SELECT p.*, u.username FROM `bi_posts` p
                    JOIN `bi_users` u ON p.author_id = u.id
                    WHERE p.community_id = :id AND p.reply_id IS NULL");
                    $sth->bindValue(':id', $sublewitid);
                    $sth->execute();
                    $sublewits = $sth->fetchAll();
                    $sth2 = $dbh->prepare("SELECT * FROM bi_communities WHERE community_id = :commid");
                    $sth2->bindValue(':commid', $sublewitid);
                    $sth2->execute();
                    $community = $sth2->fetch();
                    $communityname = $community['name'];
                    $communityinfo = $community["description"];
                } 
                catch (PDOException $e) {
                    echo "<p>Error: {$e->getMessage()}</p>";
                }

                ?>
<body>
    <div class="container">
        <?php
        require_once "header.php";
        ?>
                <?php
                echo "<h1 class='center'>" . $communityname. "</h1>";
                echo "<h3 class='center'>" . $communityinfo. "</h3>";
                ?>
    

    <?php
    try{
        if (!empty($sublewits)){


            foreach ($sublewits as $data){
                $postid = $data['id'];
        
                $upvotes = 0;
                $downvotes = 0;
                echo "<div class='post-div'>";
        
                echo "<h2 class='post-user'><a href='profile.php?id=" . htmlspecialchars($data['author_id']) . "'>" . htmlspecialchars($data['username']) . "</a></h2>";
                echo "<p class='post-sublewit'><i>" . htmlspecialchars($community['name']) . "</i></p>";
                
                echo "<p class='post-content'>" . htmlspecialchars($data['content']) . "<a href='post.php?id=" . $data['id'] . "'> Click to see post</a></p>";
                
                
        
                $interactionTable = $dbh->prepare("SELECT `interaction_type`, COUNT(`post_id`) as icnt FROM `bi_interactions` WHERE :postId = `post_id` GROUP BY `interaction_type`;");
                $interactionTable->bindValue(":postId", $postid);
                $interactionTable->execute();
                $interactions = $interactionTable->fetchAll();
                foreach($interactions as $interaction){
                    if($interaction['interaction_type'] == 1){
                        $upvotes = $interaction["icnt"];
                    }
                    else{
                        $downvotes = $interaction["icnt"];
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
            $datetime = strtotime($data['creation_time']);
            $formatted_date = date('m/d/Y h:i:s A', $datetime);
            echo "<p><i>" . htmlspecialchars($formatted_date) . "</i></p>";
            if ($data['admin_change'] != NULL) {
                echo "<p><i>This post was modified by an admin</i></p>";
            }
            echo "</div>";
            }
        
            }
        
            else{
                echo "No posts found";
            }
    }
    catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }
    ?>
    </div>
</body>

</html>