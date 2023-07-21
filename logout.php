<?php
require 'realconfig.php';
session_start();

unset($_SESSION["user"]);
$_SESSION['load-more'] = 5;


header("Location: index.php");
