<?php
   
    class Controller_Main {
        
        // my objects
        private $model_login;
        private $model_home;
        private $view_login_logout;
        private $view_home;
        private $zugriffe;
        
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
          
            // check if my session modes are set
            
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
            // array for caching data while edit OR insert and checkDuplication
            if (!isset($_SESSION['cacheData'])) {
                $_SESSION['cacheData'] = array();
            }

            
            // check session mode
            switch ($_SESSION['mode_1']) {
                case "new":
                    $_SESSION['mode_1'] = 'unlogged';
                    $this->view_login_logout->display($_SESSION['mode_3']);
                    break;
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
                case "logged":
                    // check REQUEST and handle
                    
                    // 1. User pressed LOGOUT button
                    if (isset($_GET['logout'])) {
                        $_SESSION['mode_1'] = 'unlogged';
                        $_SESSION['mode_3'] = 'legally_unlogged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $_SESSION['mode_1'] = 'unlogged';
                        $_SESSION['mode_2'] = 'default';
                        $_SESSION['mode_3'] = 'default';
                        break;
                    }
                    
                     // 2. User pressed SHOWALL DATASETS button
                    if (isset($_GET['showall'])) {
                        $_SESSION['mode_2'] = 'show';
                        array_push($_SESSION['lastview'], 'showall');
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), NULL);
                        break;
                    }
                
                    // 3. User pressed SEARCH DATASETS button
                    if (isset($_GET['search'])) {
                        $_SESSION['mode_2'] = 'show';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // if no searchterms are choosed
                        if (($_GET['searchterm_name'] == "") and ($_GET['searchterm_job'] == "") and ($_GET['searchterm_status'] == "")) {
                            // check previous view and reload 
                            if (end($_SESSION['lastview']) == 'showall') {
                                $_SESSION['mode_2'] = 'show';
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), "Sie haben keine Suchbegriffe ausgewählt");
                            } elseif (end($_SESSION['lastview']) == 'default') {
                                $_SESSION['mode_2'] = 'defaultEmptyFields';
                                $this->view_home->display($_SESSION['mode_2'], NULL, "Sie haben keine Suchbegriffe ausgewählt");
                            } else {
                                $_SESSION['mode_2'] = 'show';
                                $lastSearchTerms_array; 
                                foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                    $lastSearchTerms_array[] = $lastSearchTerms;
                                }
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), "Sie haben keine Suchbegriffe ausgewählt");
                            }
                            break;
                        }
                        array_push($_SESSION['lastview'], array($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status']));
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status']), $this->model_home->messageFromDb);
                        break;
                    }
                    
                    // 4. User pressed DELETE VIEW button
                    if (isset($_GET['deleteview'])) {
                        $_SESSION['mode_2'] = 'default';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], NULL, NULL);
                        break;
                    }
                    
                    // 7. User pressed INSERT NEW DATASET button
                    if (isset($_GET['insert'])) {
                        $_SESSION['mode_2'] = 'insert';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], NULL, NULL);
                        break;
                    }
                    
                    // 8. User pressed SAVE NEW DATASET button
                    if (isset($_GET['saveNew'])) {
                        $_SESSION['cacheData'] = array ($_GET['firstname'],
                                                        $_GET['name'],
                                                        $_GET['job'],
                                                        $_GET['status'],
                                                        $_GET['xing_profile'],
                                                        $_GET['first_contact_at'],
                                                        $_GET['first_contact_over_profile'],
                                                        $_GET['first_contact_from'],
                                                        $_GET['last_update'],
                                                        $_GET['infos']);
                        // if the new contact isnt in database
                        if (!$this->model_home->existDuplicates($_GET['firstname'], $_GET['name'], $_GET['xing_profile'])) {
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
                            $_SESSION['mode_2'] = 'saved';
                        // if there are supposed duplicates in database
                        } else {
                            if (!is_null($this->model_home->getDuplicates())) {
                                $_SESSION['mode_2'] = 'supposedDuplicated';
                                $this->view_login_logout->display($_SESSION['mode_3']);
                                $this->view_home->display($_SESSION['mode_2'], array($_SESSION['cacheData'], $this->model_home->getDuplicates()), NULL);
                                break;
                            } else {
                                $_SESSION['mode_2'] = 'duplicated';
                                $this->view_login_logout->display($_SESSION['mode_3']);
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), NULL);
                                break;
                            }
                            
                        }
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->getLastDataset(), $this->model_home->messageFromDb);
                        break;
                    }
                    
                    // 9. User pressed CONFIRM NEW Dataset Button
                    
                    if (isset($_GET['confirmInsert'])) {
                        $_SESSION['mode_2'] = 'saved';
                        $newData = $_SESSION['cacheData']; 
                        $this->model_home->insertDataset($newData[0],
                                                         $newData[1],
                                                         $newData[2],
                                                         $newData[3],
                                                         $newData[4],
                                                         $newData[5],
                                                         $newData[6],
                                                         $newData[7],
                                                         $newData[8],
                                                         $newData[9]);
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->getLastDataset(), $this->model_home->messageFromDb);
                        break;
                    }
                  
                    // 10. User pressed EDITDELETE button (also for EXPORT)
                    if (isset($_GET['editdelete'])) {
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
                                            
                        // if user choosed edit 
                        elseif($_GET['action'] == "Bearbeiten") {
                            // if user choose more than one dataset, go back to old view
                            if (count($_GET['choosed'])>1) {
                                $_SESSION['mode_2'] = 'toMuchChoosed';
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
                            }
                            // if user choose one dataset, go to edit view
                            elseif (count($_GET['choosed'])==1) {
                                $_SESSION['mode_2'] = 'edit';
                                $this->view_login_logout->display($_SESSION['mode_3']);
                                $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($_GET['choosed']), NULL);
                            }
                            break;
                        }
                        // if user choosed delete 
                        elseif($_GET['action'] == "Entfernen") {
                            $_SESSION['mode_2'] = 'delete';
                            $_SESSION['delete'] = $_GET['choosed']; // save ids to delete
                        }
                        // if user choosed CSV Export 
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
                        // if user forgot to choose delete, export or edit
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
                    
                    // 11. User pressed EDIT OLD DATASET Button
                    if (isset($_GET['editOld'])) {
                        $newData = $_SESSION['cacheData'];
                        $this->model_home->existDuplicates($newData[0],$newData[1],$newData[4]);
                        // if user choosed no datasets
                        if (!isset($_GET['choosed'])) {
                            $_SESSION['mode_2'] = 'nothingChoosedWhileValidate';
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            $this->view_home->display($_SESSION['mode_2'], array($_SESSION['cacheData'], $this->model_home->getDuplicates()), NULL);
                        }
                        // if user choose more than one dataset, go back to old view
                        elseif (count($_GET['choosed'])>1) {
                            $_SESSION['mode_2'] = 'toMuchChoosedWhileValidate';
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            // check previous view and reload
                            $this->view_home->display($_SESSION['mode_2'], array($_SESSION['cacheData'], $this->model_home->getDuplicates()), NULL);
                        }
                        // if user choose one dataset, go to edit view
                        elseif (count($_GET['choosed'])==1) {
                            $_SESSION['mode_2'] = 'edit';
                            $this->view_login_logout->display($_SESSION['mode_3']);
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($_GET['choosed']), NULL);
                        }
                        break;
                    }
                    
                    // 12. User pressed SAVE EDITED DATASET button
                    if (isset($_GET['saveEdited'])) {
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
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->getLastDataset(), $this->model_home->messageFromDb);
                        break;
                    }
                    
                    // 13. User pressed CONFIRM DELETE dataset button
                    if (isset($_GET['confirmDelIDNEU'])) {
                        $_SESSION['mode_2'] = 'deleted';
                        $this->model_home->deleteDataset($_SESSION['delete']);
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // check previous view and reload 
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets(), $this->model_home->messageFromDb);
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]), $this->model_home->messageFromDb);
                        }
                        break;
                    }
                    
                    // 14. User pressed BACK button
                    if (isset($_GET['back'])) {
                        array_push($_SESSION['lastview'], 'default');
                        $_SESSION['mode_2'] = 'default';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], NULL, NULL);
                    }
                    
                    // 15. User made anything else             
                    else {
                        $_SESSION['mode_1'] = 'unlogged';
                        $_SESSION['mode_2'] = 'default';
                        $_SESSION['mode_3'] = 'illegally_unlogged';
                        $this->view_login_logout->display($_SESSION['mode_3'], NULL);
                    }
                }
        }
        
        // little login function
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