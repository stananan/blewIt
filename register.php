<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title> Register</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["message"])) {
        echo '<p> '.$_SESSION["message"].'</p>';
        unset($_SESSION["message"]);
    }
    ?>
    <form action="registersubmit.php" method = "post">
        <h3>Username:</h3>
        <?php
        $username = "";
        $admincode = "";
        if (isset($_SESSION["username"])){
            $username = $_SESSION["username"];
            // do we need this?
            unset($_SESSION["username"]);
        }
        echo "<input type = \"text\" name = \"username\" value = \"{$username}\"  required>";
        echo "<h3>Password:</h3>";
        echo "<input type =\"text\" name = \"password\" required>";
        echo "<h3>Admin Code:</h3>";
        if (isset($_SESSION["admincode"])){
            $admincode = $_SESSION["admincode"];
            // do we need this?
            unset($_SESSION["admincode"]);
        }
        echo "<input type=\"text\" name =\"admincode\" value = \"{$admincode}\">";
        ?>
        <button type="submit">Create</button>
        
        <!-- Add js frontend validation 
        Username should only contain letters, numbers, and underscores.-->
    </form>
    
</body>


</html>