<?php
    
    /*
      class renders Headline of Page and different
      cases of Login / Logout scenarios
    */
    class View_Login_Logout {
        
        //******************************************************************************************
        
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
                    self::renderHeadline();
                    echo 
                        "<script type='text/javascript'>
                             alert('Ihr Login war leider nicht erfolgreich, bitte versuchen Sie es nochmal.');
                         </script>";
                    self::renderLoginFormular();
                    echo "</body>
                         </html>";
                    break;
                // login permitted, renders logout Button
                case 'logged':
                    self::renderHeadline();
                    self::renderLogoutFormular();
                    break;                    
                case 'legally_unlogged':
                    // secure logout, renders login formular with alert
                    self::renderHeadline();
                    echo 
                    self::renderLoginFormular();
                    echo "</body>
                        </html>";
                    break;
                case 'illegally_unlogged':
                    // unsecure handle, renders login formular with alert
                    self::renderHeadline();
                    echo 
                       "<script type='text/javascript'>
                            alert('Sie wurden durch eine unsichere Handlung [z.B. Aufruf einer alten Seite dieser Applikation] ausgeloggt. Bitte loggen Sie sich neu ein, Danke.');
                        </script>";
                    self::renderLoginFormular();
                    echo "</body>
                        </html>";
                    break;
                default:
                    // default, for example 1st page visit
                    self::renderHeadline();    
                    self::renderLoginFormular();
                    echo "</body>
                        </html>";
                    break;
            }
        }
        
        //******************************************************************************************
        
        /*
         
         function renderHeadline()
         renders headline and meta information
         
        */
        public function renderHeadline() {

            echo   "<div class='upperMargin1'>
                    </div>
                    <div class='upperMargin2'>
                    </div>
                    <div class='header'><!--BEGIN div class 'header'-->
                        <div class='headline'>
                        <font size='6' style='font-family:Helvetica'>
                            <font color='darkslategrey'><b>XING </b></font><font color='black'>KONTAKTMANAGER</font>
                        </font>
                        </div>";
        }
        
        //******************************************************************************************
        
        /*
         
         function renderLoginFormular()
         login formular
         
        */
        public function renderLoginFormular() {
            echo
                "<div class='loginFormular'>
                    <form method='GET'>
                    <table>
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
                </div>
                </div><!--END div class 'header'-->"; // 2nd "</div>" --> end class "header" "
        }
        
        //******************************************************************************************
        
        /*
         
         function renderLogoutFormular()
         logout formular
         
        */
        public function renderLogoutFormular() {
            echo 
               "<div class='logoutFormular'>
                    <form method='GET'>
                        <input class='logoutButton' type='submit' name='logout' value='Ausloggen'>
                    </form>
                </div>
                </div><!--END div class 'header'-->"; // 2nd "</div>" --> end class "header" 
        }
        
        
    }

