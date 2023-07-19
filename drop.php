<html>

<head>
    <title>Drop Blewit Database</title>
</head>

<body>
    <?php
    require "realconfig.php";
    session_start();
    if (isset($_SESSION["user"])) {
        unset($_SESSION["user"]);
    }


    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $dbh->exec('DROP TABLE IF EXISTS bi_users, bi_communities, bi_posts, bi_interactions;');
        echo "<p>Successfully dropped databases</p>";
    } catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }
    ?>
</body>

</html>