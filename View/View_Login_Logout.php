<?php
    
    /*
      class renders Headline of Page and different
      cases of Login / Logout scenarios
    */
    class View_Login_Logout {
        
        /*
          renders the scenarios
        */
        public function display($mode_3) {
            
            // render head and meta infos
            echo
                   "<html>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                        <link rel='stylesheet' type='text/css' href='View/View.css'/>
                        <link rel='stylesheet' type='text/css' href='JQuery/jquery-ui.css'/>
                        <link rel='stylesheet' type='text/css' href='JQuery/fixheadtable/css/base.css'/>
                        <script src='JQuery/jquery.min.js'></script>
                        <script src='JQuery/jquery-ui.min.js'></script>
                        <script type='text/javascript' src='JQuery/fixheadtable/javascript/jquery.fixheadertable.js'></script>
                    </head>
                    <body>";
            
            switch ($mode_3) {
                // login denied, renders login formular with alert
                case 'login_denied':
                    self::displayHeadline();
                    echo 
                        "<script type='text/javascript'>
                             alert('Ihr Login war leider nicht erfolgreich, bitte versuchen Sie es nochmal.');
                         </script>";
                    self::displayLoginFormular();
                    echo "</body>
                         </html>";
                    break;
                // login permitted, renders logout Button
                case 'logged':
                    self::displayHeadline();
                    self::displayLogoutFormular();
                    break;                    
                case 'legally_unlogged':
                    // secure logout, renders login formular with alert
                    self::displayHeadline();
                    echo 
                       "<script type='text/javascript'>
                            alert('Sie haben sich erfolgreich ausgeloggt, auf Wiedersehen.');
                        </script>";
                    self::displayLoginFormular();
                    echo "</body>
                        </html>";
                    break;
                case 'illegally_unlogged':
                    // unsecure handle, renders login formular with alert
                    self::displayHeadline();
                    echo 
                       "<script type='text/javascript'>
                            alert('Sie wurden durch eine unsichere Handlung [z.B. Aufruf einer alten Seite dieser Applikation] ausgeloggt. Bitte loggen Sie sich neu ein, Danke.');
                        </script>";
                    self::displayLoginFormular();
                    echo "</body>
                        </html>";
                    break;
                default:
                    // default, for example 1st page visit
                    self::displayHeadline();    
                    self::displayLoginFormular();
                    echo "</body>
                        </html>";
                    break;
            }
        }
        
        /*
         renders headline and meta information
        */
        public function displayHeadline(){

            echo   "<div class='header'><!--BEGIN div class 'header'-->
                        <span class='headline'>
                        <font size='7' style='font-family:Helvetica'>
                            <font color='darkslategray'><b>XING </b></font>KONTAKTMANAGER
                        </font>
                        </span>";
        }
        
        /*
         login formular
        */
        public function displayLoginFormular(){
            echo
                "<span class='logFormular'>
                    <form method='GET'>
                    <table style='margin-left:366px; margin-top:12px'>
                        <tr>
                            <td><font size='2'>Username:</font></td><td><input class='log' name='username' value='name'></td>
                        </tr>
                        <tr>
                            <td><font size='2'>Passwort:</font></td><td><input class='log' name='password' type='password' value='password'></td>
                        </tr>
                        <tr>
                            <td></td><td class='login'><input class='log' type='submit' value='Einloggen'></td>
                        </tr>
                    </table>
                    </form>
                </span>";
        }
        
                /*
         logout formular
        */
        public function displayLogoutFormular(){
            echo 
               "<span class='logFormular'>
                    <form method='GET'>
                        <input class='logoutButton' type='submit' name='logout' value='Ausloggen'>
                    </form>
                </span>";
        }
        
        
    }

