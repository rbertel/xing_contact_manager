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
                $_SESSION['mode_1'] = 'new';            
            }
            // mode for rendering homepage (how to) /// describes last user action
            if (!isset($_SESSION['mode_2'])) {
                $_SESSION['mode_2'] = 'default';
            }
            // mode for rendering login / logout (how to)
            if (!isset($_SESSION['mode_3'])) {
                $_SESSION['mode_3'] = 'default';
            }
            // stack for save get requests (case showall and search)
            // to reload view after insert/edit/delete
            if (!isset($_SESSION['lastview'])) {
                $_SESSION['lastview'] = array();
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
                            $this->view_home->display($_SESSION['mode_2'], NULL);
                        } else {
                            $this->view_login_logout->display($_SESSION['mode_3']);
                        }
                        break;
                    }
                    $this->view_login_logout->display($_SESSION['mode_3']);
                    break;
                case "logged":
                    // check REQUEST and handle
                    
                    // 1. User pressed logout button
                    if (isset($_GET['logout'])) {
                        $_SESSION['mode_1'] = 'unlogged';
                        $_SESSION['mode_3'] = 'legally_unlogged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        break;
                    }
                    
                     // 2. User pressed showall datasets button
                    if (isset($_GET['showall'])) {
                        $_SESSION['mode_2'] = 'show';
                        $_SESSION['mode_3'] = 'logged';
                        array_push($_SESSION['lastview'], 'showall');
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets());
                        break;
                    }
                
                    // 3. User pressed search datasets button
                    if (isset($_GET['search'])) {
                        $_SESSION['mode_2'] = 'show';
                        $_SESSION['mode_3'] = 'logged';
                        array_push($_SESSION['lastview'], array($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status']));
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($_GET['searchterm_name'],$_GET['searchterm_job'],$_GET['searchterm_status']));
                        break;
                    }
                    
                    // 4. User pressed delete view button
                    if (isset($_GET['deleteview'])) {
                        $_SESSION['mode_2'] = 'default';
                        $_SESSION['mode_3'] = 'logged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], NULL);
                        break;
                    }
                    
                    // 5. User pressed edit dataset button
                    if (isset($_GET['editID'])) {
                        $_SESSION['mode_2'] = 'edit';
                        $_SESSION['mode_3'] = 'logged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($_GET['editID']));
                        break;
                    }
                    
                    // 6. User pressed save edited dataset button
                    if (isset($_GET['saveID'])) {
                        $_SESSION['mode_2'] = 'saved';
                        $_SESSION['mode_3'] = 'logged';
                        $this->model_home->updateDataset($_GET['id'],
                                                         $_GET['firstname'],
                                                         $_GET['name'],
                                                         $_GET['job'],
                                                         $_GET['status'],
                                                         $_GET['first_contact_at'],
                                                         $_GET['first_contact_over_profile'],
                                                         $_GET['first_contact_from'],
                                                         $_GET['last_update'],
                                                         $_GET['infos']);
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // check previous view and reload 
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets());
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]));
                        }
                       break;
                    }
                    
                    // 7. User pressed insert new dataset button
                    if (isset($_GET['insert'])) {
                        $_SESSION['mode_2'] = 'insert';
                        $_SESSION['mode_3'] = 'logged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], NULL);
                        break;
                    }
                    
                    // 8. User pressed save new dataset button
                    if (isset($_GET['saveDS'])) {
                        $_SESSION['mode_2'] = 'saved';
                        $_SESSION['mode_3'] = 'logged';
                        $this->model_home->insertDataset($_GET['firstname'],
                                                         $_GET['name'],
                                                         $_GET['job'],
                                                         $_GET['status'],
                                                         $_GET['first_contact_at'],
                                                         $_GET['first_contact_over_profile'],
                                                         $_GET['first_contact_from'],
                                                         $_GET['last_update'],
                                                         $_GET['infos']);
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // check previous view and reload 
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets());
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]));
                        }
                        break;
                    }
                    
                    // 9. User pressed delete dataset button
                    if (isset($_GET['delID'])) {
                        $_SESSION['mode_2'] = 'delete';
                        $_SESSION['mode_3'] = 'logged';
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        $this->view_home->display($_SESSION['mode_2'], $this->model_home->filterDatasetID($_GET['delID']));
                        break;
                    }
                    
                    // 10. User pressed confirm delete dataset button
                    if (isset($_GET['confirmDelID'])) {
                        $_SESSION['mode_2'] = 'deleted';
                        $_SESSION['mode_3'] = 'logged';
                        $this->model_home->deleteDataset($_GET['confirmDelID']);
                        $this->view_login_logout->display($_SESSION['mode_3']);
                        // check previous view and reload 
                        if (end($_SESSION['lastview']) == 'showall') {
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->getDatasets());
                        } else {
                            $lastSearchTerms_array; 
                            foreach (end($_SESSION['lastview']) as $lastSearchTerms) {
                                $lastSearchTerms_array[] = $lastSearchTerms;
                            }
                            $this->view_home->display($_SESSION['mode_2'], $this->model_home->searchDataset($lastSearchTerms_array[0], $lastSearchTerms_array[1], $lastSearchTerms_array[2]));
                        }
                        break;
                    }
                    
                    // 11. User made anything else             
                    else {
                        $_SESSION['mode_1'] = 'unlogged';
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