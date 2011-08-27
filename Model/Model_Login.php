<?php
    
    /*
     * class Model_Login is an Object class to create a DB Connection and check if
     * User Login is successful
    */
    
    class Model_Login {
           
        // some variables   
        public $db_host;
        public $db_db;
        public $db_username;
        public $db_password;
        public $res;
        public $num;
        
        // connects to DB
        public function __construct() {
            
            $this->db_host        = "localhost";
            $this->db_db          = "xing_contacts";
            $this->db_username    = "xxx";
            $this->db_password    = "xxx";
            mysql_connect($this->db_host, $this->db_username, $this->db_password);
            mysql_selectdb($this->db_db);
            
        }
        
        // check if Login is successful, returns true if yes, false if not
        public function login($name, $password) {
            $this->res = mysql_query("SELECT name, password FROM admins WHERE name ='".$name."' AND password = '".$password."'");
            $this->num_rows = mysql_num_rows($this->res);
            return ($this->num_rows == 1);
        }
    }

?>