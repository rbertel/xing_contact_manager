<?php
   
   include_once("Model/Model_Login.php");
   include_once("Model/Model_Home.php");
   include_once("View/View_Login.php");
   include_once("View/View_Home.php");
   
    class Controller_Main {
               
        // my objects
        private $model_login;
        private $model_home;
        private $view_login;
        private $view_home;
        private $zugriffe;
        
        // constructor
        public function __construct() {
            $this->model_login = new Model_Login();
            $this->model_home = new Model_Home();
            $this->view_login = new View_Login();
            $this->view_home = new View_Home();
        }
        
        // invoke the main_controller
        public function invoke() {
            
                       
            // check if my session modes are setted
            if (!isset($_SESSION['mode'])) {
                $_SESSION['mode'] = 'unlogged';            
            }
            if (!isset($_SESSION['mode_2'])) {
                $_SESSION['mode_2'] = 'default';
            }
            // check session mode
            switch ($_SESSION['mode']) {
                case "unlogged":
                    $this->view_login->display();
                    self::login();
                    break;
                case "logged":
                    // check REQUEST and handle
                    if (isset($_GET['logout'])) {
                        $_SESSION['mode'] = 'unlogged';
                        $this->view_login->display();
                        break;
                    }

                    if (isset($_GET['showall'])) {
                        $_SESSION['mode_2'] = 'show';
                        $this->view_login->display();
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->datasets);
                        break;
                    }
                   
                    if (isset($_GET['search'])) {
                        $_SESSION['mode_2'] = 'show';
                        $this->view_login->display();
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($_GET['searchterm'],$_GET['searchtype']));
                        break;
                    }
                    
                    if (isset($_GET['deleteview'])) {
                        $_SESSION['mode_2'] = 'default';
                        $this->view_login->display();
                        $this->view_home->display($_SESSION['mode_2']);
                        break;
                    }            
            }
        }
        
        // little login function
        public function login() {
            if (isset($_GET['username']) && isset($_GET['password'])) {
                $login = $this->model_login->login($_GET['username'],$_GET['password']);
                if ($login) {
                    $_SESSION['mode'] =  'logged';
                    $_SESSION['mode_2'] = 'default';
                    $this->view_home->display($_SESSION['mode_2']);
                }
            }
        }
        
      
        
        
    }
    
?>