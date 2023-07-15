<html>

<head>
    <title>Drop Blewit Database</title>
</head>

<body>
    <?php
    require_once "database.php";

    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $dbh->exec('DROP TABLE IF EXISTS bi_interactions; DROP TABLE IF EXISTS bi_users; DROP TABLE IF EXISTS bi_posts;  DROP TABLE IF EXISTS bi_community;');
        echo "<p>Successfully dropped databases</p>";
    } catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }
    ?>
</body>

</html>