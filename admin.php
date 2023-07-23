<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="style.css">
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


        <div class="posts-container">
            <div class="post-div">
                <h2>Users</h2>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Creation Time</th>
                        <th>Last Logged In</th>


                        <th>Delete button</th>
                    </tr>
                    <?php
                    try {
                        $sth = $dbh->prepare("SELECT * FROM bi_users WHERE `is_admin` = 0;");


                        $sth->execute();
                        $users = $sth->fetchAll();
                        foreach ($users as $user) {
                            $userid = $user['id'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                            echo "<td><a href='profile.php?id=" . htmlspecialchars($user['id']) . "'>" . htmlspecialchars($user['username']) . "</a></td>";

                            $datetime = strtotime($user["creation_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . htmlspecialchars($formatted_date) . "</td>";

                            $datetime = strtotime($user["last_login_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . htmlspecialchars($formatted_date) . "</td>";

                            echo "<td><a href = \"deleteuser.php?id={$userid}\">DELETE USER</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }

                    ?>
                </table>
            </div>
            <div class="post-div">
                <h2>Posts</h2>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Content</th>
                        <th>Type</th>
                        <th>Creation Time</th>
                        <th>Author Id</th>
                        <th>Community Id</th>
                        <th>Changed By Admin</th>
                        <th>Delete button</th>
                        <th>Edit Button</th>
                    </tr>
                    <?php
                    try {
                        $sth = $dbh->prepare("SELECT * FROM bi_posts ORDER BY `creation_time` DESC");
                        $sth->execute();
                        $posts = $sth->fetchAll();
                        foreach ($posts as $post) {
                            $postid = $post['id'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($post['id']) . "</td>";

                            $contentSubstring = substr($post['content'], 0, 30);
                            if (strlen($post['content']) > 30) {
                                $contentSubstring .= "...";
                            }
                            echo "<td><a href='post.php?id=" . $post['id'] . "'>" . htmlspecialchars($contentSubstring) . "</a></td>";

                            $type = "Post";
                            if ($post['reply_id'] != NULL) $type = "Comment";
                            echo  "<td>" . $type . "</td>";
                            $datetime = strtotime($post["creation_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . htmlspecialchars($formatted_date) . "</td>";

                            echo "<td><a href='profile.php?id=" . htmlspecialchars($post['author_id']) . "'>" . htmlspecialchars($post['author_id']) . "</td>";

                            echo "<td>" . $post['community_id'] . "</td>";
                            if ($post['admin_change'] != NULL) {
                                echo "<td>Yes</td>";
                            } else {
                                echo "<td>False</td>";
                            }



                            echo "<td><a href = \"deletepost.php?id={$postid}\">DELETE POST</a></td>";

                            echo "<td><a href = \"updatepost.php?id={$postid}\">UPDATE POST</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }

                    ?>
                </table>
            </div>
            <div class="post-div">
                <h2>Sublewits</h2>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Creator Id</th>
                        <th>Delete button</th>
                    </tr>
                    <?php
                    try {
                        $sth = $dbh->prepare("SELECT * FROM bi_communities");
                        $sth->execute();
                        $sublewits = $sth->fetchAll();
                        foreach ($sublewits as $sublewit) {
                            $sublewitId = $sublewit['id'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($sublewit['id']) . "</td>";

                            echo "<td>" . htmlspecialchars($sublewit['name']) . "</td>";

                            $contentSubstring = substr($sublewit['description'], 0, 30);
                            if (strlen($sublewit['description']) > 30) {
                                $contentSubstring .= "...";
                            }
                            echo "<td>" . htmlspecialchars($contentSubstring) . "</td>";
                            if ($sublewit['user_id'] == 0) {
                                echo "<td>PRESET SUBLEWIT</td>";
                            } else {
                                echo "<td><a href='profile.php?id=" . htmlspecialchars($sublewit['user_id']) . "'>" . htmlspecialchars($sublewit['user_id']) . "</td>";
                            }


                            echo "<td><a href = \"deletesublewit.php?id={$sublewitId}\">DELETE SUBLEWIT</a></td>";

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