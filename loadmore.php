<?php
session_start();

$_SESSION['load-more'] += 5;

echo $_SESSION['load-more'];

header("Location: index.php");
