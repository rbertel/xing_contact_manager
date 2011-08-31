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
        private  $view;
        
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
          getView
        */
        public function getView() {
            return $this->view;
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
          delete one dataset
          regarding the id
        */
        public function deleteDataset($id) {
            
            // connect DB
            self::connectDB();
            
            // update dataset
            $this->db->query  ("DELETE FROM contacts WHERE id = ".$id);
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
           $this->view = $all_entries;
           return $all_entries;
        }
        
        //**********************************************************************************
               
        /*
        search for dataset
        params $terms (name / firstname), $job, $status --> all searchterms!
        */
        public function searchDataset($terms, $job, $status) {
            
            // variable for searchresults
            $searchresults = NULL;
            
            // if terms is empty search only for job / status
            if ($terms == NULL) {
                foreach(self::getDatasets() as $actual_dataset) {
                    // 4 cases = 4 kind ofs search
                    // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        if (($job == $actual_dataset['job']) and
                           (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $searchresults[] = $actual_dataset;       
                        }
                    }
                    // job is empty / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        if (stristr($actual_dataset['status'], $status) != FALSE) {
                            $searchresults[] = $actual_dataset;
                            
                        }
                    }
                    // job is not empty / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        if ($job == $actual_dataset['job']) {
                            $searchresults[] = $actual_dataset;
                        }
                    }
                     // job and status are empty
                    elseif (($job == "") and ($status  == "")) {
                        return $searchresults;
                    }
                }
                $this->view = $searchresults;
                return $searchresults;
            }
            
            // if terms is not empty continue with deleting whitespaces at the begin of terms
            $terms = trim($terms);

            // if terms is emtpy after trimming search only for job / status
            if ($terms == "") {
                foreach(self::getDatasets() as $actual_dataset) {
                    // 4 cases = 4 kind ofs search
                    // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        if (($job == $actual_dataset['job']) and
                           (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $searchresults[] = $actual_dataset;       
                        }
                    }
                    // job is emtpy / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        if (stristr($actual_dataset['status'], $status) != FALSE) {
                            $searchresults[] = $actual_dataset;
                            
                        }
                    }
                    // job is not emtpy / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        if ($job == $actual_dataset['job']) {
                            $searchresults[] = $actual_dataset;
                        }
                    }
                     // job and status are empty
                    elseif (($job == "") and ($status  == "")) {
                        return $searchresults;
                    }
                }
                $this->view = $searchresults;
                return $searchresults;
            } 
            
            // if terms is not empty continue with converting my searchterms in an array
            $term_array = explode(" ", $terms);
            
            // variable for checkin existence of term in dataset
            $term_exists;
            
            // search in datasets for terms
            foreach(self::getDatasets() as $actual_dataset) {
                foreach ($term_array as $term) {
                     // 4 cases = 4 kind ofs search
                     // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        if ((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name'])) and
                            ($job == $actual_dataset['job']) and
                            (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job is emtpy / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        if ((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name'])) and
                            (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job is not emtpy / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        if ((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name'])) and
                            ($job == $actual_dataset['job'])) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job and status are empty
                    elseif (($job == "") and ($status  == "")) {
                        if ((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name']))) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                }
                if ($term_exists) {
                    $searchresults[] = $actual_dataset;
                }
            }
            $this->view = $searchresults;
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
        
        /*
          little helper function to compare strings with "umlauts"
          turns all chars in term to lower
        */
        
        public function termToLower ($term) {
            
            $term = utf8_decode($term);
            $term = mb_strtolower($term);
            $term = utf8_encode($term);
            return $term;
        
        }
        

        
        //**********************************************************************************
        
        
        
        
        
        
}

?>