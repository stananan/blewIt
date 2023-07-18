<?php
require 'realconfig.php';
session_start();

try {
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);


        $username = htmlspecialchars($_POST['username']);
        $userpassword = htmlspecialchars($_POST['password']);

        $sth = $dbh->prepare("SELECT * FROM `bi_users` WHERE :username = `username`;");
        $sth->bindValue(':username', $username);
        if ($sth->execute()) {
            $loginUser = $sth->fetch();

            if (!isset($loginUser['password'])) {
                header("Location: login.php");
                $_SESSION["message"] = "Incorrent Credentials";
            } else {

                if (password_verify($userpassword, $loginUser['password'])) {
                    $userTable = $dbh->prepare("UPDATE `bi_users` SET `last_login_time` = NOW() WHERE :username = `username`;");
                    $userTable->bindValue(":username", $username);
                    $userTable->execute();
                    $_SESSION["user"] = $loginUser;
                    header("Location: index.php");
                } else {
                    header("Location: login.php");
                    $_SESSION["message"] = "Incorrent Password";
                }
            }
        } else {
            echo "Error, please try again";
        }
    } else {
        echo "Not valid username or password!";
    }
} catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();


    echo $e;
}