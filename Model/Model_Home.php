<?php
    
    class Model_Home {
               
        // some variables   
        public $db_host;
        public $db_db;
        public $db_username;
        public $db_password;
        public $res;
        public $num;
        public $datasets;
        
        //**********************************************************************************
        
        // connects to DB
        public function __construct() {
                        
            $this->db_host        = "localhost";
            $this->db_db          = "xing_contacts";
            $this->db_username    = "xxx";
            $this->db_password    = "xxx";
            
            // $database = new PDO("mysql:host=" . db_host . ";dbname=" . db_db, db_username, db_password);
            // $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            mysql_connect($this->db_host, $this->db_username, $this->db_password);
            mysql_selectdb($this->db_db);
            
            $this->datasets = self::getDatasets();
        
        }
        
        //**********************************************************************************
        
        // insert new dataset
        public function insertDataset($id, $firstname, $name, $email, $telephone, $status,
                                      $fca, $fcop, $fcf, $lu, $infos) {
            
            $this->res = mysql_query ("INSERT INTO contacts (id, firstname, name, email,
                                      telephone, status, first_contact_at, first_contact_over_profile,
                                      first_contact_from, last_update, infos) VALUES ('".$id."',
                                      '".$firstname."','". $name."','".$email."','".$telephone."',
                                      '".$status."','".$fca."','".$fcop."','".$fcf."','". $lu."',
                                      '".$infos."')");           
        } 
        
        //**********************************************************************************
        
        // update one dataset regarding the id 
        public function updateDataset($id, $firstname, $name, $email, $telephone, $status,
                                      $fca, $fcop, $fcf, $lu, $infos) {
            
            $this->res =  mysql_query("UPDATE contacts SET firstname = '".$firstname."',
                                       name = '".$name."', email = '".$email."',
                                       telephone = '".$telephone."', status = '".$status."',
                                       first_contact_at = '".$fca."', first_contact_over_profile = '".$fcop."',
                                       first_contact_from = '".$fcf."', last_update = '".$lu."',
                                       infos = '".$infos."' WHERE id = ".$id);            
        }
        
        //**********************************************************************************
        
        /* gets all datasets
           returns array with all datasets */ 
        public function getDatasets() {
            
            $this->res = mysql_query("SELECT * FROM contacts");
            $this->num = mysql_num_rows($this->res);
            
            // fill $entries with all datasets
            $i = 0;
            while($entry = mysql_fetch_assoc($this->res)){
                
                $all_entries[$i] = array (
                    'id' => $entry['id'],
                    'firstname' => $entry['firstname'],
                    'name' => $entry['name'],
                    'email' => $entry['email'],
                    'telephone' => $entry['telephone'],
                    'status' => $entry['status'],
                    'first_contact_at' => $entry['first_contact_at'],
                    'first_contact_over_profile' => $entry['first_contact_over_profile'],
                    'first_contact_from' => $entry['first_contact_from'],
                    'last_update' => $entry['last_update'],
                    'infos' => $entry['infos']);
                    $i++;    
            }
            return $all_entries;
        }
        
        //**********************************************************************************
               
        /*
        search for dataset
        params $term (searchterm) and $type (searchtype)
        returns array of datasets who contain right result
        */
        public function searchDataset($term, $type) {
           
           $searchresults;
           
           foreach($this->datasets as $actual_dataset) {
                foreach ($actual_dataset as $oneentry) {
                    if (stristr($actual_dataset[$type], $term) != false) {
                        $searchresults[] = $actual_dataset;
                        break;
                    }  
                }
            }
            return $searchresults;
        }
}

?>