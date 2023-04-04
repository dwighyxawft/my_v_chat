<?php
session_start();
require("classes/DB.php");
require("classes/User.php");
define("BASE_URL", "http://localhost/my_v_chat/");
$userObj = new \MyApp\User;

?>