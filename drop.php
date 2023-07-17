<html>

<head>
    <title>Drop Blewit Database</title>
</head>

<body>
    <?php
    require "realconfig.php";

    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $dbh->exec('DROP TABLE IF EXISTS bi_posts, bi_interactions, bi_users, bi_communities;');
        echo "<p>Successfully dropped databases</p>";
    } catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }
    ?>
</body>

</html>