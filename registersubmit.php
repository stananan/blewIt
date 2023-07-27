<?php
require 'realconfig.php';
session_start();
//Backend for Registering
// We get a sql error if it didnt go through so we try to insert a new user to the db, if we get a error, show the error in the register page
try {
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        if (strlen($_POST["username"]) < 3 || strlen($_POST["password"]) < 3) {
            header("Location: register.php");
            $_SESSION["message"] = "Invalid Username or password. Too short";
            die();
        }

        if (!ctype_alpha($_POST["username"])) {
            header("Location: register.php");
            $_SESSION["message"] = "Invalid Username. No numbers or special characters";
            die();
        }

        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

        $isadmin = 0;

        if (isset($_POST["admincode"])) {
            if (password_verify($_POST["admincode"], adminCode)) {
                $isadmin = 1;
            }
        }
        $username = $_POST['username'];
        $userpassword = password_hash($_POST["password"], PASSWORD_DEFAULT);


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

            $userisadmin = $dbh->prepare("SELECT * FROM bi_users WHERE :username = username;");
            $userisadmin->bindValue(":username", $username);
            $userisadmin->execute();
            $_SESSION["admin"] = $userisadmin->fetch()['is_admin'];
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
