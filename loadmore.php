<?php
//Load more backend
session_start();

if ($_GET['page'] == 0 && isset($_SESSION['load-more-index'])) {
    $_SESSION['load-more-index'] += 5;
    header("Location: index.php");
}


header("Location: index.php");
