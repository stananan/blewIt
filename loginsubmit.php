<?php
require 'realconfig.php';
session_start();

try {
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);


        $username = $_POST['username'];
        $userpassword = $_POST['password'];

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
                    $_SESSION["user"] = $loginUser['id'];
                    $userisadmin = $dbh->prepare("SELECT * FROM bi_users WHERE :username = username;");
                    $userisadmin->bindValue(":username", $username);
                    $userisadmin->execute();
                    $_SESSION["admin"] = $userisadmin->fetch()['is_admin'];
                    header("Location: index.php");
                } else {
                    header("Location: login.php");
                    $_SESSION["message"] = "Incorrent Password";
                }
            }
        } else {

            header("Location: login.php");
            $_SESSION["message"] = "Error, please try again";
        }
    } else {
        echo "Not valid username or password!";
    }
} catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();
    header("Location: login.php");
    $_SESSION["message"] = $errormessage;

    echo $e;
}
