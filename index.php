<?php

session_start();
   
include_once("Model/Model_Login.php");
include_once("Model/Model_Home.php");
include_once("View/View_Login_Logout.php");
include_once("View/View_Home.php");
include_once("Controller/Controller_Main.php");

// load Controller
$controller_main = new Controller_Main(new Model_Login(), new Model_Home(),
                                       new View_Login_Logout(), new View_Home());
$controller_main->invoke();

//session_unset();

?>