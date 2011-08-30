<?php
    
    /*
      class Model_Home is the main interface to
      the database, it can connect, insert,
      update, search...
    */ 
    
    class Model_Home {
        
        //**********************************************************************************
               
        // some variables
        private $db;
        private $db_username;
        private $db_password;
        
        //**********************************************************************************
        
        /*
          constructor
        */
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
                $this->db->query("SET NAMES 'utf8'");
            } catch (PDOException $e) {
                echo "Fehler: ".$e->getMessage();
                die();
            }
        }
        
        //**********************************************************************************
        
        /*
          insert new dataset
        */
        public function insertDataset($firstname, $name, $job, $status,
                                      $fca, $fcop, $fcf, $lu, $infos) {
           
            self::connectDB();
            $this->db->query  ("INSERT INTO contacts (id, firstname, name, job,
                                status, first_contact_at, first_contact_over_profile,
                                first_contact_from, last_update, infos) VALUES (NULL,
                                '".$firstname."','". $name."','".$job."','".$status."',
                                '".$fca."','".$fcop."','".$fcf."','". $lu."',
                                '".$infos."')");
            
            $this->db = NULL;
            
        }
        
        //**********************************************************************************
        
        /*
          update one dataset
          regarding the id
        */
        public function updateDataset($id, $firstname, $name, $job, $status,
                                      $fca, $fcop, $fcf, $lu, $infos) {
            // connect DB
            self::connectDB();
            
            // update dataset
            $this->db->query  ("UPDATE contacts SET firstname = '".$firstname."',
                                name = '".$name."', job = '".$job."',
                                status = '".$status."', first_contact_at = '".$fca."',
                                first_contact_over_profile = '".$fcop."',
                                first_contact_from = '".$fcf."', last_update = '".$lu."',
                                infos = '".$infos."' WHERE id = ".$id);
            
            $this->db = NULL;
        }
        
        //**********************************************************************************
        
        /*
          gets all datasets
          returns array with all datasets
        */ 
        public function getDatasets() {
            
            // connect DB
            self::connectDB();
                        
            // fill $entries with all datasets
            $i = 0;
            foreach ($this->db->query("SELECT * FROM contacts ORDER BY `id` ASC") as $entry) {
                $all_entries[$i] = array (
                    'id' => $entry['id'],
                    'firstname' => $entry['firstname'],
                    'name' => $entry['name'],
                    'job' => $entry['job'],
                    'status' => $entry['status'],
                    'first_contact_at' => $entry['first_contact_at'],
                    'first_contact_over_profile' => $entry['first_contact_over_profile'],
                    'first_contact_from' => $entry['first_contact_from'],
                    'last_update' => $entry['last_update'],
                    'infos' => $entry['infos']
                );
                $i++;    
            }
           $this->db = NULL;
           return $all_entries;
        }
        
        //**********************************************************************************
               
        /*
        search for dataset
        params $term (searchterm) and $type (searchtype)
        returns array of datasets who contain right result
        */
        public function searchDataset($term, $type) {
 
           // variable to initialize an array
           $searchresults;
           foreach(self::getDatasets() as $actual_dataset) {
                foreach ($actual_dataset as $oneentry) {
                    if (stristr($actual_dataset[$type], $term) != false) {
                        $searchresults[] = $actual_dataset;
                        break;
                    }  
                }
            }
            return $searchresults;
        }
        
        
        //**********************************************************************************
               
        /*
        NEW search for dataset
        params $term (searchterm) and $type (searchtype)
        returns array of datasets who contain right result
        */
        public function newSearchDataset($terms) {
            
            
            // if terms is emtpy return NULL
            if ($terms == NULL) {
                return NULL;
            }
                        
            // delete whitespaces at the begin of terms
            $terms = trim($terms);
            
            // if terms is emtpy return NULL
            if ($terms == "") {
                return NULL;
            }

            // my searchterms in an array
            $term_array = explode(" ", $terms);
            
            // delete whitespaces at the begin of the single terms
            foreach ($term_array as $term) {
                if (($term == "") or ($term == NULL)) {
                    unset($term);
                }
            }
            
            echo count($term_array);

            // variable to initialize an array
            $searchresults;
            
            // variable for checkin existence of term in dataset
            $term_exists;
            
            // search in datasets for terms
            foreach(self::getDatasets() as $actual_dataset) {
                foreach ($term_array as $term) {
                    if ($term == $actual_dataset['firstname'] or $term == $actual_dataset['name'] or $term == $actual_dataset['job']  or (stristr($actual_dataset['status'], $term) != false)) {
                        $term_exists = TRUE;
                    } else {
                        $term_exists = FALSE;
                        break;   
                    }
                }
                if ($term_exists) {
                    $searchresults[] = $actual_dataset;
                }
            }
            return $searchresults;
        }
        
        //**********************************************************************************
               
        /*
            returns last ID of datasets
        */
        public function getLastID() {
            $datasets = self::getDatasets();
            return $datasets[count($datasets)-1]['id'];
        }
        
        //**********************************************************************************
        
        /*
        search for dataset
        params $id (searchterm)
        returns dataset which contains right result
        */
        public function filterDatasetID($id) {
           $searchresults;
           foreach(self::getDatasets() as $actual_dataset) {
                foreach ($actual_dataset as $oneentry) {
                    if ($actual_dataset['id'] == $id) {
                        $searchresults[] = $actual_dataset;
                        break;
                    }  
                }
            }
            return $searchresults;
        }
        
        //**********************************************************************************
        

        
        //**********************************************************************************
        
        
        
        
        
        
}

?>