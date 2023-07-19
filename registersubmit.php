<?php
require 'realconfig.php';
session_start();
//we have to dedicate a day to some backend validation
try {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

        $isadmin = 0;

        if (isset($_POST["admincode"])) {
            if (strcmp(htmlspecialchars($_POST["admincode"]), "admin123") == 0) {
                $isadmin = 1;
            }
        }
        $username = htmlspecialchars($_POST['username']);
        $userpassword = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT);


        $sth = $dbh->prepare("INSERT INTO bi_users (`username`, `password`, `is_admin`, `creation_time`, `last_login_time`)
            VALUES (:username, :userpassword, :isadmin, NOW(), NOW());");
        $sth->bindValue(':username', $username);
        $sth->bindValue(":userpassword", $userpassword);
        $sth->bindValue(":isadmin", $isadmin);
        if ($sth->execute()) {
            echo "User successfully created";

            $userTable = $dbh->prepare("SELECT * FROM bi_users WHERE :username = username;");
            $userTable->bindValue(":username", $username);
            $userTable->execute();
            $_SESSION["user"] = $userTable->fetch()['id'];
            header("Location: index.php");
        } else {
            echo "Error, please try again";
        }
    } else {
        header("Location: register.php");
        $_SESSION["message"] = "Not valid username or password!";
    }
} catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();
    if (str_contains($errormessage, "1062 Duplicate entry") && $errorcode == 23000) {

        header("Location: register.php");
        $_SESSION["message"] = "Username already exists, please create another one.";
    } else if (str_contains($errormessage, "1406 Data too long for column 'username'") && $errorcode == 22001) {

        header("Location: register.php");
        $_SESSION["message"] = "Username is too long";
    } else {
        header("Location: register.php");
        $_SESSION["message"] = "Error creating user";
    }


    echo $e;
}
