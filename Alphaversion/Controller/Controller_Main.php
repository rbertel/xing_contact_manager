<?php
   
    class Controller_Main {
        
        // my objects
        private $model_login;
        private $model_home;
        private $view_login_logout;
        private $view_home;
        private $dbMessage;
        
        // constructor
        public function __construct(Model_Login $model_login, Model_Home $model_home,
                                    View_Login_Logout $view_login_logout, View_Home $view_home) {
            
            $this->model_login = $model_login;
            $this->model_home  = $model_home;
            $this->view_login_logout  = $view_login_logout;
            $this->view_home   = $view_home;
        
        }
        
        // invoke the main_controller
        public function invoke() {
          
            // check if my session modes are set and set if not
            
            // general mode (new / logged / unlogged)
            if (!isset($_SESSION['mode_1'])) {
                $_SESSION['mode_1'] = 'unlogged';            
            }
            // mode for rendering homepage (how to) /// describes last actual user action
            if (!isset($_SESSION['mode_2'])) {
                $_SESSION['mode_2'] = 'default';
            }
            // mode for rendering login / logout (how to)
            if (!isset($_SESSION['mode_3'])) {
                $_SESSION['mode_3'] = 'default';
            }
            // stack for save get requests (case showall, search, default)
            // to reload view after insert/edit/delete
            if (!isset($_SESSION['lastview'])) {
                $_SESSION['lastview'] = array();
                array_push($_SESSION['lastview'], 'default');
            }
            // mode for cache save state of new dataset while insert/filter            
            if (!isset($_SESSION['new_saved'])) {
                $_SESSION['new_saved'] = FALSE;
            }
            // mode for save state if dataset is inserted immediately after filter           
            if (!isset($_SESSION['insert_immediately_state'])) {
                $_SESSION['insert_immediately_state'] = FALSE;
            }

            
            // check session mode 1 ( general mode --> 2 possibilites -> logged or unlogged ) 
            switch ($_SESSION['mode_1']) {
                
                // user is unlogged
                case "unlogged":
                    if (isset($_GET['username']) && isset($_GET['password'])) {
                        if(self::login()) {
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            $this->view_home->display($_SESSION['mode_2'], NULL, NULL);
                        } else {
                            $this->view_login_logout->display($_SESSION['mode_3']);
                        }
                        break;
                    }
                    $this->view_login_logout->display($_SESSION['mode_3']);
                    break;
                
                // user is logged
                case "logged":
                    
                    /*
                       check requests and handle depending on kind of request
                    */
                    
                    // 1. User pressed "AUSLOGGEN" button
                    if (isset($_GET['logout'])) {
                        // set user unlogged
                        $_SESSION['mode_3'] = 'legally_unlogged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        session_unset();
                        break;
                    }
                    
                    //*****************************************
                    // START FILTER MODES FILTER, EDIT, INSERT
                    //*****************************************    
     
                    // 2. User pressed "ANLEGEN" button
                    if (isset($_GET['filter'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        array_push($_SESSION['lastview'], 'default');
                        $_SESSION['new_saved'] = FALSE;
                        // caching filterterms (firstname and name) for Edit View respectively Reload Validate Table
                        $_SESSION['cache_filterterms'] = array($_GET['filter_firstname'], $_GET['filter_name']); 
                        // if no terms are there to filter, show all datasets
                        if ($_GET['filter_name'] == "" and $_GET['filter_firstname'] == "") {
                            $_SESSION['mode_2'] = 'show';
                            array_push($_SESSION['lastview'], 'showall');
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), $this->model_home->messageFromDb);
                        } else {
                            $filter_results = $this->model_home->filterDataset($_GET['filter_firstname'], $_GET['filter_name']);
                            // if no duplicates are found, go to insert table
                            if ($filter_results == NULL) {
                                $_SESSION['insert_immediately_state'] = TRUE;
                                $_SESSION['mode_2'] = 'insert_immediately';
                                $this->view_home->display($_SESSION['mode_2'], $_SESSION['cache_filterterms'], $this->model_home->messageFromDb.", sie können den Kontakt neu anlegen:");
                            // else go to validate table
                            } else {
                                $_SESSION['mode_2'] = 'filter_new_is_unsaved';
                                // send filterterms and filterresults to View
                                $this->view_home->display($_SESSION['mode_2'], array($_GET['filter_firstname'], $_GET['filter_name'], $filter_results), $this->model_home->messageFromDb);
                            }
                        }
                        break;
                    }
                    
                    // 3. User pressed "PLUS" icon to create new dataset
                    if (isset($_GET['insert'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['insert_immediately_state'] = FALSE;
                        $_SESSION['mode_2'] = 'filter_insert';
                        $this->view_home->display($_SESSION['mode_2'], $_SESSION['cache_filterterms'], NULL);
                        break;
                    }
                    
                    
                    // 4. User pressed "DATENSATZ SPEICHERN" button (in "NEUEN DATENSATZ ERSTELLEN")
                    if (isset($_GET['save_inserted'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->model_home->insertDataset($_GET['firstname'],
                                                         $_GET['name'],
                                                         $_GET['job'],
                                                         $_GET['status'],
                                                         $_GET['xing_profile'],
                                                         $_GET['first_contact_at'],
                                                         $_GET['first_contact_over_profile'],
                                                         $_GET['first_contact_from'],
                                                         $_GET['last_update'],
                                                         $_GET['infos']);
                        $dbMessage = $this->model_home->messageFromDb;
                        $_SESSION['mode_2'] = 'filter_new_is_saved';
                        // get filterterms form Session
                        $filter_terms = $_SESSION['cache_filterterms'];
                        // prophylatic check if user change names in insert formular 
                        if($_SESSION['insert_immediately_state']) {
                            $filter_terms[0] = $_GET['firstname'];
                            $filter_terms[1] = $_GET['name'];
                        }
                        // clean filterterms_cache
                        $_SESSION['new_saved'] = TRUE;
                        // send filterresults to View
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDataset($filter_terms[0], $filter_terms[1]), $dbMessage);
                        break;
                    }
                    
                    // 5. User pressed "ALTEN DATENSATZ BEARBEITEN" pin icon
                    if (isset($_GET['edit_old'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_2'] = 'edit_old';
                        $ids = array();
                        $ids[] = $_GET['edit_old'];
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($ids), NULL);
                        break;
                    }
                    
                    // 6. User pressed "DATENSATZ SPEICHERN" button (Edited Dataset)
                    if (isset($_GET['save_edited_old'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->model_home->updateDataset($_GET['id'],
                                                         $_GET['firstname'],
                                                         $_GET['name'],
                                                         $_GET['job'],
                                                         $_GET['status'],
                                                         $_GET['xing_profile'],
                                                         $_GET['first_contact_at'],
                                                         $_GET['first_contact_over_profile'],
                                                         $_GET['first_contact_from'],
                                                         $_GET['last_update'],
                                                         $_GET['infos']);
                        $dbMessage = $this->model_home->messageFromDb;
                        if (($_SESSION['new_saved']) == TRUE) {
                            $_SESSION['mode_2'] = 'filter_new_is_saved';
                            $filter_terms = $_SESSION['cache_filterterms'];
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDataset($filter_terms[0], $filter_terms[1]), $dbMessage);
                        } else {
                            $_SESSION['mode_2'] = 'filter_new_is_unsaved';
                            $filter_terms = $_SESSION['cache_filterterms'];
                            $filter_results = $this->model_home->filterDataset($filter_terms[0], $filter_terms[1]);
                            $this->view_home->display($_SESSION['mode_2'], array($filter_terms[0], $filter_terms[1], $filter_results), $dbMessage);
                        }
                        break;
                    }
                    
                    //*****************************************
                    // END FILTER MODES FILTER, EDIT, INSERT
                    //***************************************** 
                    
                    // 7. User pressed "SUCHEN" button
                    if (isset($_GET['search'])) {
                        $_SESSION['mode_2'] = 'show';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // if no searchterms are choosed
                        if (($_GET['searchterm_name'] == "") and ($_GET['searchterm_job'] == "") and ($_GET['searchterm_status'] == "")) {
                            // check previous view and reload 
                            if (end($_SESSION['lastview']) == 'showall') {
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), "Sie haben keine Suchbegriffe ausgewählt");
                            } elseif (end($_SESSION['lastview']) == 'default') {
                                $_SESSION['mode_2'] = 'defaultEmptyFields';
                                $this->view_home->display($_SESSION['mode_2'], NULL, "Sie haben keine Suchbegriffe ausgewählt");
                            } else {
                                $lastSearchTerms_array; 
                                foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                    $lastSearchTerms_array[] = $lastSearchTerms;
                                }
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), "Sie haben keine Suchbegriffe ausgewählt");
                            }
                            break;
                        }
                        // save last searchterms on stack
                        array_push($_SESSION['lastview'], array($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status'])); 
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status']), $this->model_home->messageFromDb);
                        break;
                    }
                    
                    // 8. User pressed "ENTFERNEN / EXPORT" action
                    if (isset($_GET['deleteExport'])) {
                        // if user choosed no datasets
                        if (!isset($_GET['choosed'])) {
                            $_SESSION['mode_2'] = 'nothingChoosed';
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            // check previous view and reload
                            if (end($_SESSION['lastview']) == 'showall') {
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), NULL);
                            } else {
                                $lastSearchTerms_array; 
                                foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                    $lastSearchTerms_array[] = $lastSearchTerms;
                                }
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), NULL);
                            }
                            break;
                        }
                        // if user choosed "LÖSCHEN" 
                        elseif($_GET['action'] == "Entfernen") {
                            $_SESSION['mode_2'] = 'delete';
                            $_SESSION['delete'] = $_GET['choosed']; // save ids to delete
                        }
                        // if user choosed "CSV Export" 
                        elseif($_GET['action'] == "CSV Export") {
                            $_SESSION['mode_2'] = 'export';
                            $this->model_home->createCSV($_GET['choosed']);
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            // check previous view and reload
                            if (end($_SESSION['lastview']) == 'showall') {
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), NULL);
                            } else {
                                $lastSearchTerms_array; 
                                foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                    $lastSearchTerms_array[] = $lastSearchTerms;
                                }
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), NULL);
                            }
                            break;
                        }
                        // if user forgot to choose delete or export
                        elseif($_GET['action'] == "") {
                            $_SESSION['mode_2'] = 'noActionChoosed';
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            // check previous view and reload
                            if (end($_SESSION['lastview']) == 'showall') {
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), NULL);
                            } else {
                                $lastSearchTerms_array; 
                                foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                    $lastSearchTerms_array[] = $lastSearchTerms;
                                }
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), NULL);
                            }
                            break;
                        }
                        // default rendering actions
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($_GET['choosed']), NULL);
                        break;
                    }
            
                    // 9. User pressed "DATENSATZ BEARBEITEN" pin icon
                    if (isset($_GET['edit'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_2'] = 'edit';
                        $ids = array();
                        $ids[] = $_GET['edit'];
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($ids), NULL);
                        break;
                    }
                    
                    // 10. User pressed "DATENSATZ SPEICHERN" button (Edited Dataset)
                    if (isset($_GET['saveEdited'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_2'] = 'saved';
                        $this->model_home->updateDataset($_GET['id'],
                                                         $_GET['firstname'],
                                                         $_GET['name'],
                                                         $_GET['job'],
                                                         $_GET['status'],
                                                         $_GET['xing_profile'],
                                                         $_GET['first_contact_at'],
                                                         $_GET['first_contact_over_profile'],
                                                         $_GET['first_contact_from'],
                                                         $_GET['last_update'],
                                                         $_GET['infos']);
                        $dbMessage = $this->model_home->messageFromDb;
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), $dbMessage);
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]),  $dbMessage);
                        }
                        break;
                    }
                    
                    // 11. User pressed "LÖSCHEN" dataset button (CONFIRM DELETE)
                    if (isset($_GET['confirmDelIDNEU'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_2'] = 'deleted';
                        $this->model_home->deleteDataset($_SESSION['delete']);
                        $dbMessage = $this->model_home->messageFromDb;
                        // check previous view and reload 
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), $dbMessage);
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), $dbMessage);
                        }
                        break;
                    }
                    
                    // 12. User pressed "ZURÜCK" button
                    if (isset($_GET['back'])) {
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_2'] = 'show';
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), $this->model_home->messageFromDb);
                        } elseif (end($_SESSION['lastview']) == 'default') {
                            $_SESSION['mode_2'] = 'defaultEmptyFields';
                            $this->view_home->display($_SESSION['mode_2'], NULL, "Sie haben keine Suchbegriffe ausgewählt");
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), "Sie haben keine Suchbegriffe ausgewählt");
                        }
                        break;
                    }
                    
                    // 13. User made anything else             
                    else {
                        $_SESSION['mode_1'] = 'unlogged';
                        $_SESSION['mode_2'] = 'default';
                        $_SESSION['mode_3'] = 'illegally_unlogged';
                        $this->view_login_logout->display($_SESSION['mode_3'], NULL);
                    }
                }
        }
        
        /*
          login()
          checks login process
          return true if username/password are right
          return false if not
        */
        public function login() {
                        
            $login = $this->model_login->login($_GET['username'],$_GET['password']);
            if ($login) {
                $_SESSION['mode_1'] =  'logged';
                $_SESSION['mode_2'] = 'default';
                $_SESSION['mode_3'] = 'logged';
                return true;
            } else {
                $_SESSION['mode_1'] =  'unlogged';
                $_SESSION['mode_2'] = 'default';
                $_SESSION['mode_3'] = 'login_denied';
                return false;
            }
        }
        
      
        
        
    }
    
?>