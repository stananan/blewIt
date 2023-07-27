<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
// Frontend for Register page
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

</head>

<body>
    <div class="container">

        <?php
        require_once "header.php";
        ?>

        <div class="posts-container">
            <div class="post-div">
                <?php

                if (isset($_SESSION["message"])) {
                    echo '<p> ' . $_SESSION["message"] . '</p>';
                    unset($_SESSION["message"]);
                }

                ?>
                <!-- register form -->
                <form action="registersubmit.php" method="post">
                    <h3>Username:</h3>
                    <?php
                    $username = "";
                    $admincode = "";

                    echo "<input type = 'text' name = 'username' required>";
                    echo "<h3>Password:</h3>";
                    echo "<input type ='password' name = 'password' required>";
                    echo "<h3>Admin Code:</h3>";



                    echo "<input type='password' name = 'admincode'>";
                    ?>
                    <button type="submit">Create</button>

                    <!-- Add js frontend validation 
        Username should only contain letters, numbers, and underscores.-->
                </form>
            </div>
        </div>
    </div>


</body>

</html>