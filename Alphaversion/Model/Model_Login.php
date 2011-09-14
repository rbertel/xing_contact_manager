<?php
    
    /*
     * class Model_Login is an Object class to create a DB Connection and check if
     * User Login is successful
    */
    
    class Model_Login {
           
        // some variables   
        public $db;
        public $db_username;
        public $db_password;
        
        // connects to DB
        public function __construct() {
            
            $this->db_username    = "root";
            $this->db_password    = "banane030";
            
        }
    
        //**********************************************************************************
        
        /*
          connects to DB
        */
        public function connectDB() {
            
            try {
                $this->db = new PDO('mysql:host=localhost;dbname=xing_contacts',
                                     $this->db_username, $this->db_password);
            } catch (PDOException $e) {
                echo "Fehler: ".$e->getMessage();
                die();
            }
        }
        
        //**********************************************************************************
          
        
        
        // check if Login is successful, returns true if yes, false if not
        public function login($name, $password) {  
            
            self::connectDB();
            $loginQuery = $this->db->query("SELECT name, password FROM admins WHERE name ='".$name."' AND password = '".$password."'");
            foreach ($loginQuery as $entry)  {
                return (!$entry[0] == NULL);
            }
        }
    }

?>