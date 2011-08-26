<?php
    
    // EINE TESTDATEI
    
    class Zugriffe {
        
            // some variables   
            public $db_host;
            public $db_db;
            public $db_username;
            public $db_password;        
            
            public function __construct() {
                        
                $this->db_host        = "localhost";
                $this->db_db          = "xing_contacts";
                $this->db_username    = "xxx";
                $this->db_password    = "xxx";
                mysql_connect($this->db_host, $this->db_username, $this->db_password);
                mysql_selectdb($this->db_db);
                mysql_query("INSERT INTO zugriffe (name) VALUE ('". $_SESSION['mode']."')");
                
            }
        
        
    }    
        
        