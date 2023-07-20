<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

// if (!isset(($_GET['id']))) {
//     http_response_code(404);
//     echo "Error 404: Page not found";
//     exit();
// }

// try {
//     $usersTableCheck = $dbh->prepare("SELECT `id` from `bi_users`");
//     $usersTableCheck->execute();
//     $usersCheck = $usersTableCheck->fetchAll();
//     $check = false;
//     foreach ($usersCheck as $id) {

//         if ($id['id'] == htmlspecialchars($_GET['id'])) {
//             $check = true;
//         }
//     }
//     if ($check == false) {
//         http_response_code(404);
//         echo "Error 404: Page not found";
//         exit();
//     }
// } catch (PDOException $e) {
//     echo "<p>Error: {$e->getMessage()}</p>";
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="style.css">
    <style>
        td{
            padding:10px;
            border:1px solid black;
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
                        if($_SESSION["admin"]==1){
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
                <table>
                    <tr>
                        <td>Number</td>
                        <td>Username</td>
                        <td>Delete button</td>
                    </tr>
                    <?php
                try {
                    $sth = $dbh->prepare("SELECT * FROM bi_users");
                    $sth->execute();
                    $users = $sth->fetchAll();
                    foreach($users as $user){
                        $userid = $user['id'];
                        echo "<tr>";
                        echo "<td>" . $user['id'] . "</td>";
                        echo "<td>" . $user['username'] . "</td>";
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
                <table>
                    <tr>
                        <td>Number</td>
                        <td>Content</td>
                        <td>Delete button</td>
                    </tr>
                    <?php
                try {
                    $sth = $dbh->prepare("SELECT * FROM bi_posts");
                    $sth->execute();
                    $posts = $sth->fetchAll();
                    foreach($posts as $post){
                        $postid = $post['id'];
                        echo "<tr>";
                        echo "<td>" . $post['id'] . "</td>";
                        echo "<td>" . $post['content'] . "</td>";
                        echo "<td><a href = \"deletepost.php?id={$postid}\">DELETE POST</a></td>";
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