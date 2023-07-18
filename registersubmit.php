<?php
require 'realconfig.php';
session_start();

try {
        if(isset($_POST["username"]) && isset($_POST["password"])){
        
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
            // $sth2 = $dbh->prepare("SELECT * FROM bi_users ORDER BY RAND() LIMIT 1");
            // $sth2->execute();
            $isadmin = 0;
            if(isset($_POST["admincode"])){
                if(strcmp($_POST["admincode"] , "admin123") == 0){
                    $isadmin = 1;
                    // echo"hi";
                }

            }
            $username = $_POST['username'];
            $userpassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
            echo $userpassword;
            echo"<br>";
            $sth = $dbh->prepare("INSERT INTO bi_users (`username`, `password`,`is_admin`, `create_time`)
            VALUES (:username, :userpassword, :isadmin, now())");
            $sth->bindValue(':username', $username);
            $sth->bindValue(":userpassword", $userpassword);
            $sth->bindValue(":isadmin", $isadmin);
            $newuser = $sth->execute();
            echo"User successfully created";
        }
        
        else{
            echo "Not valid username or password!";
        }
    
    

}
catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();
    if(str_contains($errormessage, "1062 Duplicate entry") && $errorcode == 23000){
        echo "Username already exists, please create another one.";
        header("Location: register.php"); 
        $_SESSION["message"] = "Username already exists, please create another one.";
        $_SESSION["username"] = $_POST['username'];
        $_SESSION["admincode"] = $_POST['admincode'];
        

    }
    else{
        echo "error creating user.";
    }
    echo "<br>";
    echo "<br>";
    echo $e;
} 

?>