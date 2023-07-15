<html>
<head>
    <title>Install Chalkboard Manifesto DB</title>
</head>
<body>
<?php
require_once "realconfig.php";
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    //create comic table
    $query = file_get_contents('database.sql');
    $dbh->exec($query);
    echo "<p>Successfully installed databases</p>";
}
catch (PDOException $e) {
    echo "<p>Error: {$e->getMessage()}</p>";
}
// https://www.php.net/manual/en/function.file-get-contents.php -> documentation for file_get_contents()
// https://www.php.net/manual/en/pdo.exec.php -> documentation for exec()
?>
</body>
</html>