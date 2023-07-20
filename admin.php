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
                            echo "<td>" . $user['id'] . "</td>";
                            echo "<td><a href='profile.php?id=" . $user['id'] . "'>" . $user['username'] . "</a></td>";

                            $datetime = strtotime($user["creation_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . $formatted_date . "</td>";

                            $datetime = strtotime($user["last_login_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . $formatted_date . "</td>";

                            echo "<td><a href = \"deleteuser.php?id={$userid}\">DELETE USER</a></td>";
                            echo "</tr>";
                            $_SESSION["user{$userid}"] = $userid;
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
                        <th>Delete button</th>
                    </tr>
                    <?php
                    try {
                        $sth = $dbh->prepare("SELECT * FROM bi_posts");
                        $sth->execute();
                        $posts = $sth->fetchAll();
                        foreach ($posts as $post) {
                            $postid = $post['id'];
                            echo "<tr>";
                            echo "<td>" . $post['id'] . "</td>";

                            $contentSubstring = substr($post['content'], 0, 30);
                            if (strlen($post['content']) > 30) {
                                $contentSubstring .= "...";
                            }
                            echo "<td><a href='post.php?id=" . $post['id'] . "'>" . $contentSubstring . "</a></td>";

                            $type = "Post";
                            if ($post['reply_id'] != NULL) $type = "Comment";
                            echo  "<td>" . $type . "</td>";
                            $datetime = strtotime($post["creation_time"]);
                            $formatted_date = date('m/d/Y h:i:s A', $datetime);
                            echo "<td>" . $formatted_date . "</td>";

                            echo "<td><a href='profile.php?id=" . $post['author_id'] . "'>" . $post['author_id'] . "</td>";

                            echo "<td>" . $post['community_id'] . "</td>";


                            echo "<td><a href = \"deletepost.php?id={$postid}\">DELETE POST</a></td>";
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
                            echo "<td>" . $sublewit['id'] . "</td>";

                            echo "<td>" . $sublewit['name'] . "</td>";

                            $contentSubstring = substr($sublewit['description'], 0, 30);
                            if (strlen($sublewit['description']) > 30) {
                                $contentSubstring .= "...";
                            }
                            echo "<td>" . $contentSubstring . "</td>";

                            echo "<td><a href='profile.php?id=" . $sublewit['user_id'] . "'>" . $sublewit['user_id'] . "</td>";
                            echo "<td><a href = \"deletesublewit.php?id={$sublewitId}\">DELETE SUBLEWIT</a></td>";

                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: {$e->getMessage()}</p>";
                    }

                    ?>
                </table>
            </div>
            <a href="drop.php">Drop tables</a>
            <a href="install.php">Install tables</a>
        </div>
    </div>
</body>

</html>