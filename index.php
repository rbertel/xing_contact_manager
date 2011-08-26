<?php

session_start();
include_once("Controller/Controller_Main.php");

// load Controller
$controller_main = new Controller_Main();
$controller_main->invoke();

//session_unset();

?>