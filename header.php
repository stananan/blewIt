<!-- Header Code -->
<!-- ALL PAGES USE HEADER CODE-->

<div class="header-div">


    <h1 class="header-title"><a href='index.php'><img id="logo-img" src="images/reddit-logo.png" alt="logo" width="60px" height="60px"></a>Blew It</h1>


    <div class="header-links">
        <div class='nav'>
            <form action='search.php' method='get'><input type='text' name='search-val' placeholder='Search...'><button type='submit'><img id='search-img' src='images/search-bar-01.png' alt='d' width='10px' height='10px'></button></form>
        </div>

        <?php

        if (isset($_SESSION["user"])) {
            try {


                $userNameTable = $dbh->prepare("SELECT `username` FROM bi_users WHERE :id = `id`");
                $userNameTable->bindValue(":id", $_SESSION["user"]);
                $userNameTable->execute();
                $userName = $userNameTable->fetch();
                if ($_SESSION["admin"] == 1) {
                    echo "<div class='nav'><a href='admin.php'>Admin controls</a></div>";
                }

                $sth = $dbh->prepare("SELECT * FROM bi_communities");
                $sth->execute();
                $communities = $sth->fetchAll();

                echo "<div class='nav'><a href='profile.php?id=" . $_SESSION['user'] . "'>" . $userName['username'] . "</a></div>";



                echo "<div class='nav'><a href='index.php'>Home</a></div>

                <div class='nav'><a href='references.php'>References</a></div>";

                echo "<div class='nav'><a href='logout.php'>Log out</a></div>";
            } catch (PDOException $e) {

                echo "<p>Error: {$e->getMessage()}</p>";
            }
        } else {
        ?>


            <div class="nav"><a href="login.php">Login</a></div>

            <div class="nav"><a href="register.php">Register</a></div>

            <div class='nav'><a href='index.php'>Home</a></div>

            <div class='nav'><a href="references.php">References</a></div>



        <?php
        }

        ?>


    </div>

</div>