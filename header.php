<!-- Header Code -->
<div class="header-div">
    <h1 class="header-title">Blew It</h1>
    <div class="header-links">

        <?php

        if (isset($_SESSION["user"])) {
            try {
                echo "<div class='nav'><form action='search.php' method='get'><input type = 'text' name = 'search-val'><button type='submit'>Search</button></form></div>";
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
                if (isset($_SESSION['user'])) {
                    echo "<div class='nav'>";
                    echo "<div class='dropdown'>";
                    echo "<button type='button' class='dropbutton'>Communities</button>";
                    echo "<div class='dropdowncontent'>";
                    foreach ($communities as $community) {
                        $id = $community['id'];
                        $name = $community['name'];
                        echo "<a href = \" sublewit.php?id={$id}\">{$name}</a>";
                    }
                    echo " </div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='nav'><a href='profile.php?id=" . $_SESSION['user'] . "'>" . $userName['username'] . "</a></div>";
                    echo "<div class='nav'><a href='logout.php'>Log out</a></div>";
                    echo "<div class='nav'><a href='index.php'>Home</a></div>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: {$e->getMessage()}</p>";
            }
        } else {
        ?>
            <div class="nav"><a href="login.php">Login</a></div>

            <div class="nav"><a href="register.php">Register</a></div>

            <div class='nav'><a href='index.php'>Home</a></div>
        <?php
        }
        ?>
    </div>

</div>