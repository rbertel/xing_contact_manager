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
            
            switch ($mode_3) {
                // login denied, renders login formular with alert
                case 'login_denied':
                    self::displayHeadline();
                    echo 
                                "<script type='text/javascript'>
                                     alert('Ihr Login war leider nicht erfolgreich, bitte versuchen Sie es nochmal.');
                                 </script>
                                 <form method='GET'>
                                    <table>
                                        <tr>
                                            <td>Username:</td><td><input name='username' value='name'></td>
                                        </tr>
                                        <tr>
                                            <td>Passwort:</td><td><input name='password' type='password' value='password'></td>
                                        </tr>
                                        <tr>
                                            <td></td><td class='login'><input type='submit' value='Einloggen'></td>
                                        </tr>
                                 </form>
                             </body>
                         </html>";
                    break;
                // login permitted, renders logout Button
                case 'logged':
                    self::displayHeadline();
                    echo 
                       "<form method='GET'>
                            <input class='logoutButton' type='submit' name='logout' value='Ausloggen'>
                        </form>";
                    break;                    
                case 'legally_unlogged':
                    // secure logout, renders login formular with alert
                    self::displayHeadline();
                    echo 
                               "<script type='text/javascript'>
                                    alert('Sie haben sich erfolgreich ausgeloggt, auf Wiedersehen.');
                                </script>
                                 <form method='GET'>
                                    <table>
                                        <tr>
                                            <td>Username:</td><td><input name='username' value='name'></td>
                                        </tr>
                                        <tr>
                                            <td>Passwort:</td><td><input name='password' type='password' value='password'></td>
                                        </tr>
                                        <tr>
                                            <td></td><td class='login'><input type='submit' value='Einloggen'></td>
                                        </tr>
                                 </form>
                            </body>
                        </html>";
                    break;
                case 'illegally_unlogged':
                    // unsecure handle, renders login formular with alert
                    self::displayHeadline();
                    echo 
                               "<script type='text/javascript'>
                                    alert('Sie wurden durch eine unsichere Handlung [z.B. Aufruf einer alten Seite dieser Applikation] ausgeloggt. Bitte loggen Sie sich neu ein, Danke.');
                                </script>
                                 <form method='GET'>
                                    <table>
                                        <tr>
                                            <td>Username:</td><td><input name='username' value='name'></td>
                                        </tr>
                                        <tr>
                                            <td>Passwort:</td><td><input name='password' type='password' value='password'></td>
                                        </tr>
                                        <tr>
                                            <td></td><td class='login'><input type='submit' value='Einloggen'></td>
                                        </tr>
                                 </form>
                            </body>
                        </html>";
                    break;
                default:
                    // default, for example 1st page visit
                    self::displayHeadline();    
                    echo 
                               "<form method='GET'>
                                    <table>
                                        <tr>
                                            <td>Username:</td><td><input name='username' value='name'></td>
                                        </tr>
                                        <tr>
                                            <td>Passwort:</td><td><input name='password' type='password' value='password'></td>
                                        </tr>
                                        <tr>
                                            <td></td><td class='login'><input type='submit' value='Einloggen'></td>
                                        </tr>
                                 </form>
                            </body>
                        </html>";
            }
        }
        
        /*
         renders headline and meta information
        */
        public function displayHeadline(){
            echo
                "<html>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                        <link rel='stylesheet' type='text/css' href='View/View.css'/>
                        <link rel='stylesheet' type='text/css' href='JQuery/jquery-ui.css'/>
                        <script src='JQuery/jquery.min.js'></script>
                        <script src='JQuery/jquery-ui.min.js'></script>
                        <script src='JQuery/jquery.tablesorter.min.js'></script>
                        <script src='JQuery/jquery-1.6.2.js'></script>
                        
                        
                    </head>
                    <body>
                        <font size='7' style='font-family:Helvetica'>
                            XING KONTAKTMANAGER
                        </font><br><br>";
        }
        
    }

