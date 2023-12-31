<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_GET['search-val']) || empty($_GET['search-val'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

    <style>
        td,
        th {
            padding: 10px;
            border: 1px solid black;
        }
    </style>
</head>

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
        WHERE p.reply_id IS NULL
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
        <!-- Displaying search results -->
        <div class="posts-container">

            <div class="post-div">
                <table>
                    <tr>
                        <th>Users</th>
                        <th>Profile Link</th>
                    </tr>
                    <?php
                    try {
                        $userTable = $dbh->prepare("SELECT `id`, `username` FROM bi_users WHERE `username` LIKE :search");
                        $search = "%" . $_GET['search-val'] . "%";
                        $userTable->bindValue(":search", $search);
                        $userTable->execute();
                        $users = $userTable->fetchAll();
                        if ($users == NULL) {
                            echo "<tr>";
                            echo "<td>NO USERS FOUND</td>";
                            echo "<td>NO USERS FOUND</td>";
                            echo "</tr>";
                        }


                        foreach ($users as $user) {
                            echo "<tr>";
                            echo "<td>" . $user['username'] . "</td>";
                            echo "<td><a href = profile.php?id=" . $user['id'] . ">Profile Link</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }
                    ?>
                </table>
            </div>
            <div class="post-div">
                <table>
                    <tr>
                        <th>Posts</th>
                        <th>Post Link</th>
                    </tr>
                    <?php
                    try {
                        $postTable = $dbh->prepare("SELECT `id`, `content` FROM bi_posts WHERE `content` LIKE :search");
                        $search = "%" . $_GET['search-val'] . "%";
                        $postTable->bindValue(":search", $search);
                        $postTable->execute();
                        $posts = $postTable->fetchAll();
                        if ($posts == NULL) {
                            echo "<tr>";
                            echo "<td>NO POSTS FOUND</td>";
                            echo "<td>NO POSTS FOUND</td>";
                            echo "</tr>";
                        }


                        foreach ($posts as $post) {
                            echo "<tr>";
                            $contentSubstring = substr($post['content'], 0, 40);
                            if (strlen($post['content']) > 30) {
                                $contentSubstring .= "...";
                            }
                            echo "<td>" . $contentSubstring . "</td>";
                            echo "<td><a href='post.php?id=" . $post['id'] . "'>Post Link</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }
                    ?>
                </table>
            </div>
            <div class="post-div">
                <table>
                    <tr>
                        <th>Sublewits</th>
                        <th>Description</th>
                        <th>Sublewit Link</th>
                    </tr>
                    <?php
                    try {
                        $sublewitTable = $dbh->prepare("SELECT `id`, `name`, `description` FROM bi_communities WHERE `name` LIKE :search");
                        $search = "%" . $_GET['search-val'] . "%";
                        $sublewitTable->bindValue(":search", $search);
                        $sublewitTable->execute();
                        $sublewits = $sublewitTable->fetchAll();
                        if ($sublewits == NULL) {
                            echo "<tr>";
                            echo "<td>NO SUBLEWITS FOUND</td>";
                            echo "<td>NO SUBLEWITS FOUND</td>";
                            echo "<td>NO SUBLEWITS FOUND</td>";
                            echo "</tr>";
                        }


                        foreach ($sublewits as $sublewits) {
                            echo "<tr>";
                            echo "<td>" . $sublewits['name'] . "</td>";
                            echo "<td>" . $sublewits['description'] . "</td>";
                            echo "<td><a href = \" sublewit.php?id={$sublewits['id']}\">Sublewit Link</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }
                    ?>
                </table>
            </div>

        </div>


    </div>
</body>

</html>