<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
//Frontend for upddating sublewit
if (!isset($_SESSION['user']) || !isset($_GET["id"]) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("location: index.php");
}
try {

    $sublewitTableCheck = $dbh->prepare("SELECT `id` from `bi_communities`");
    $sublewitTableCheck->execute();
    $sublewitCheck = $sublewitTableCheck->fetchAll();
    $check = false;
    foreach ($sublewitCheck as $id) {

        if ($id['id'] == $_GET['id']) {
            $check = true;
        }
    }
    if ($check == false) {
        http_response_code(404);
        echo "<h1 style='text-align: center;'>Error 404: Page not found</h1>";

        echo "<h1 style='text-align: center;'>This Sublewit does not exist or it was deleted by a moderator</h1>";
        exit();
    }
    $sublewitTable = $dbh->prepare("SELECT * FROM `bi_communities` WHERE :getId = `id`;");
    $sublewitTable->bindValue(':getId', $_GET['id']);
    $sublewitTable->execute();

    $sublewit = $sublewitTable->fetch();
} catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
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
                try {

                    echo "<h1>EDIT SUBLEWIT " . htmlspecialchars($sublewit['name']) . "</h1>";
                    echo "<form action='updatesublewitconfirm.php?id=" . htmlspecialchars($_GET['id']) . "' method='post'>";
                    echo "<h2>Title</h2>";
                    echo "<textarea name='title-val' cols='20' rows='1' required style='resize: none;' maxlength='20'>" . htmlspecialchars($sublewit['name']) . "</textarea>";
                    echo "<h2>Description</h2>";
                    echo "<textarea name='content-val' cols='30' rows='10' required style='resize: none;' maxlength='1024'>" . htmlspecialchars($sublewit['description']) . "</textarea>";
                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";
                } catch (PDOException $e) {


                    $errormessage = $e->getMessage();
                    $errorcode = $e->getCode();


                    echo $e;
                }
                ?>
            </div>
        </div>
    </div>


</body>

</html>
<?php
