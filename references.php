<?php
require "realconfig.php";
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>References</title>
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
                <h1>References</h1>
                <h2><a href="https://www.reddit.com/">logo</a></h2>
                <h2><a href="https://www.cdnfonts.com/verdana.font">Font</a></h2>
            </div>
        </div>
    </div>


</body>

</html>