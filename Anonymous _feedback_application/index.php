<?php

session_start();

require_once __DIR__ . "/vendor/autoload.php";


// Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//   header('Location: app/Views/index.php');
//   exit;
// }

define('IMG_DIR', '../app/Views/images/');

require_once 'app/Routes/routes.php';

session_unset();