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
        public  $messageFromDb;
        
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
        public function insertDataset($firstname, $name, $job, $status, $xing_profile,
                                      $fca, $fcop, $fcf, $lu, $infos) {
           
            self::connectDB();
            $this->db->query  ("INSERT INTO contacts (id, firstname, name, job,
                                status, xing_profile, first_contact_at, first_contact_over_profile,
                                first_contact_from, last_update, infos) VALUES (NULL,
                                '".$firstname."','". $name."','".$job."','".$status."',
                                '".$xing_profile."','".$fca."','".$fcop."','".$fcf."','". $lu."',
                                '".$infos."')");
            
            $this->db = NULL;
            $this->messageFromDb = "Der neue Datensatz ' $firstname, $name, $job, $status, ... ' wurde gespeichert";
        }
        
        //**********************************************************************************
        
        /*
          update one dataset
          regarding the id
        */
        public function updateDataset($id, $firstname, $name, $job, $status, $xing_profile,
                                      $fca, $fcop, $fcf, $lu, $infos) {
            // connect DB
            self::connectDB();
            
            // update dataset
            $this->db->query  ("UPDATE contacts SET firstname = '".$firstname."',
                                name = '".$name."', job = '".$job."',
                                status = '".$status."', xing_profile = '".$xing_profile."',
                                first_contact_at = '".$fca."',
                                first_contact_over_profile = '".$fcop."',
                                first_contact_from = '".$fcf."', last_update = '".$lu."',
                                infos = '".$infos."' WHERE id = ".$id);
            
            $this->db = NULL;
            $this->messageFromDb = "Der Datensatz ' $firstname, $name, $job, $status, ... ' wurde gespeichert";
        }

        //**********************************************************************************
        
        /*
          delete one dataset
          regarding the id
        */
        public function deleteDataset($ids) {
            
            // connect DB
            self::connectDB();
            
            // update dataset
            foreach ($ids as $id) {
                $this->db->query  ("DELETE FROM contacts WHERE id = ".$id);
            }
            $this->db = NULL;
            if (count($ids) == 1) {
                $this->messageFromDb = "Der Datensatz wurde gelöscht";
            } else {
                 $this->messageFromDb = "Die Datensätze wurden gelöscht";
            }
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
                    'xing_profile' => $entry['xing_profile'],
                    'first_contact_at' => $entry['first_contact_at'],
                    'first_contact_over_profile' => $entry['first_contact_over_profile'],
                    'first_contact_from' => $entry['first_contact_from'],
                    'last_update' => $entry['last_update'],
                    'infos' => $entry['infos']
                );
                $i++;    
            }
            $this->db = NULL;
            $this->messageFromDb = 'Alle '.count($all_entries).' Datensätze:';
            return $all_entries;
        }
        
        //**********************************************************************************

        /*
          gets the last dataset in 
        */ 
        public function getLastDataset() {
            
            // connect DB
            self::connectDB();
                        
            // fill $entries with all datasets
            $i = 0;
            foreach ($this->db->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 1") as $entry) {
                $all_entries[$i] = array (
                    'id' => $entry['id'],
                    'firstname' => $entry['firstname'],
                    'name' => $entry['name'],
                    'job' => $entry['job'],
                    'status' => $entry['status'],
                    'xing_profile' => $entry['xing_profile'],
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
           filter for name / firstname
           params name / firstname
        */
        
        public $supposed_duplicates = NULL;
        
        public function filterDataset($firstname, $name) {

            $firstname = trim($firstname);
            $name = trim($name);
            
            foreach(self::getDatasets() as $actual_dataset) {
                if ((self::termToLower($actual_dataset['firstname']) == self::termToLower($firstname)) and
                    (self::termToLower($actual_dataset['name']) == self::termToLower($name))) {
                     $this->supposed_duplicates[] = $actual_dataset;
                }
            }
            
            // count supposed duplicates
            $countDuplicates = count($this->supposed_duplicates);
            
            // create message
            if ($countDuplicates == 0) {
                $this->messageFromDb = "Für den Namen '$firstname $name' wurden keine vermeintlichen Duplikate gefunden";
            } elseif ($countDuplicates == 1) {
                $this->messageFromDb = "Für den Namen '$firstname $name' wurde 1 vermeintliches Duplikat gefunden";
            }    else {
                $this->messageFromDb = "Für den Namen '$firstname $name' wurden $countDuplicates Duplikate gefunden";;
            }
            return $this->supposed_duplicates;
        }
        
               
        /*
        search for dataset
        params $terms (name / firstname), $job, $status --> all searchterms!
        */
        public function searchDataset($terms, $job, $status) {
  
            //variable for save searchterms ($job, $status) for message to user
            $searchterms_js = NULL;
            // variable for searchresults
            $searchresults = NULL;
            
            // if terms is empty search only for job / status
            if ($terms == "") {
                foreach(self::getDatasets() as $actual_dataset) {
                    // 4 cases = 4 kind ofs search
                    // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($job, $status);
                        if (($job == $actual_dataset['job']) and
                           (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $searchresults[] = $actual_dataset;       
                        }
                    }
                    // job is empty / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($status);
                        if (stristr($actual_dataset['status'], $status) != FALSE) {
                            $searchresults[] = $actual_dataset;
                        }
                    }
                    // job is not empty / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($job);
                        if ($job == $actual_dataset['job']) {
                            $searchresults[] = $actual_dataset;
                        }
                    }
                }
                // message to user
                $this->messageFromDb = 'Ihre Suche nach "'.implode(", ", $searchterms_js).'" lieferte '.count($searchresults).' Treffer';
                return $searchresults;
            }
            
            // if terms is not empty continue with deleting whitespaces at the begin of terms
            $terms = trim($terms);

            // if terms is emtpy after trimming search only for job / status
            if (is_null($terms) or $terms == "") {
                foreach(self::getDatasets() as $actual_dataset) {
                    // 4 cases = 4 kind ofs search
                    // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($job, $status);
                        if (($job == $actual_dataset['job']) and
                           (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $searchresults[] = $actual_dataset;       
                        }
                    }
                    // job is emtpy / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($status);
                        if (stristr($actual_dataset['status'], $status) != FALSE) {
                            $searchresults[] = $actual_dataset;
                            
                        }
                    }
                    // job is not emtpy / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        // save searchterms for message to user
                        $searchterms_js = array ($job);
                        if ($job == $actual_dataset['job']) {
                            $searchresults[] = $actual_dataset;
                        }
                    }
                }
                // message to user
                $this->messageFromDb = 'Ihre Suche nach "'.implode(", ", $searchterms_js).'" lieferte '.count($searchresults).' Treffer';
                return $searchresults;
            } 
            
            // if terms is not empty continue with converting my searchterms in an array
            $term_array = explode(" ", $terms);
            
            // variable for checkin existence of term in dataset
            $term_exists;
            
            //echo $terms;
            
            // search in datasets for terms
            foreach(self::getDatasets() as $actual_dataset) {
                foreach ($term_array as $term) {
                     // 4 cases = 4 kind ofs search
                     // job and status are not empty
                    if (($job != "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array($job, $status);
                        if (((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                             (self::termToLower($term) == self::termToLower($actual_dataset['name']))) and
                             ($job == $actual_dataset['job']) and
                             (stristr($actual_dataset['status'], $status) != FALSE) ) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job is emtpy / status is not empty
                    elseif (($job == "") and ($status  != "")) {
                        // save searchterms for message to user
                        $searchterms_js = array($status);
                        if (((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name']))) and
                            (stristr($actual_dataset['status'], $status) != FALSE)) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job is not emtpy / status is empty
                    elseif (($job != "") and ($status  == "")) {
                        // save searchterms for message to user
                        $searchterms_js = array($job);
                        if (((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name']))) and
                            ($job == $actual_dataset['job'])) {
                            $term_exists = TRUE;
                        } else {
                            $term_exists = FALSE;
                            break; 
                        }   
                    }
                    // job and status are empty
                    elseif (($job == "") and ($status  == "")) {
                        // save searchterms for message to user
                        $searchterms_js = array("");
                        if (((self::termToLower($term) == self::termToLower($actual_dataset['firstname'])) or
                            (self::termToLower($term) == self::termToLower($actual_dataset['name'])))) {
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
            // 2 kind of messages to user
            if ($searchterms_js == NULL) { // if no job or status is searched
                $this->messageFromDb = 'Ihre Suche nach "'.implode(", ", $term_array).'" lieferte '.count($searchresults).' Treffer';
            } else {
                $this->messageFromDb = 'Ihre Suche nach "'.implode(", ", $term_array).', '.implode(", ", $searchterms_js).'" lieferte '.count($searchresults).' Treffer';
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
        params $id (array of ids to search)
        returns dataset which contains right result
        */
        public function filterDatasetID($ids) {
        
            $searchresults;
            foreach(self::getDatasets() as $actual_dataset) {
                foreach ($ids as $id) {
                    if ($actual_dataset['id'] == $id) {
                        $searchresults[] = $actual_dataset;
                    }
                }
            }
            return $searchresults;
        }
        
        //**********************************************************************************
        
        /*
          Puts selected Data into CSV file for following import to Excel
        */
        
        public function createCSV($ids) {
                        
            $datei = fopen("export.csv", 'w');
            fwrite($datei, '"ID","VORNAME","NAME","TÄTIGKEIT","STATUS","XING PROFIL","ERSTER KONTAKT AM","ERSTER KONTAKT ÜBER PROFIL","ERSTER KONTAKT ÜBER MA","LETZTES UPDATE","INFOS"');
                        
            foreach(self::filterDatasetID($ids) as $actual_dataset) {
                
                fwrite($datei, "\r\n");           
                fwrite($datei, '"'.$actual_dataset['id'].'",');
                fwrite($datei, '"'.$actual_dataset['firstname'].'",');
                fwrite($datei, '"'.$actual_dataset['name'].'",'); 
                fwrite($datei, '"'.$actual_dataset['job'].'",');
                fwrite($datei, '"'.$actual_dataset['status'].'",');
                fwrite($datei, '"'.$actual_dataset['xing_profile'].'",');
                fwrite($datei, '"'.$actual_dataset['first_contact_at'].'",');
                fwrite($datei, '"'.$actual_dataset['first_contact_over_profile'].'",');
                fwrite($datei, '"'.$actual_dataset['first_contact_from'].'",');
                fwrite($datei, '"'.$actual_dataset['last_update'].'",');
                fwrite($datei, '"'.$actual_dataset['infos'].'"'); 
            }
            
            fclose($datei);
            
            $dl_datei = "export.csv";
                        
            header('Content-type: text/plain');
            header('Content-Length:'.filesize($dl_datei));
            header('Content-Disposition: attachment; filename='.$dl_datei);
            readfile('export.csv');
            unlink('export.csv');
            exit();
        
        }
        
        
        //**********************************************************************************
        
        /*
          little helper function to compare strings with "umlauts"
          turns all chars in term to lower and validate strings in utf8
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