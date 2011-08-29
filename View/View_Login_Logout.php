<?php

    class View_Login_Logout {
                
        public function display($mode_3) {
            
            switch ($mode_3) {
                case 'login_denied':
                    echo 
                       "<html>
                            <head></head>
                            <body>
                                <script type='text/javascript'>
                                    alert('Ihr Login war leider nicht erfolgreich, bitte versuchen Sie es nochmal.');
                                </script>
                                <form method='GET'>
                                    Username: <input name='username' value='name'><br>
                                    Passwort: <input name='password' type='password' value='password'><br>
                                    <input type='submit' value='Einloggen'>
                                </form>
                            </body>
                        </html>";
                    break;
                case 'logged':
                    echo 
                       "<html>
                            <head></head>
                            <body>
                                <form method='GET'>
                                    <input type='submit' name='logout' value='Ausloggen'>
                                </form>
                            </body>
                        </html>";
                    break;                    
                case 'legally_unlogged':
                    echo 
                       "<html>
                            <head></head>
                            <body>
                                <script type='text/javascript'>
                                    alert('Sie haben sich erfolgreich ausgeloggt, auf Wiedersehen.');
                                </script>
                                <form method='GET'>
                                    Username: <input name='username' value='name'><br>
                                    Passwort: <input name='password' type='password' value='password'><br>
                                    <input type='submit' value='Einloggen'>
                                </form>
                            </body>
                        </html>";
                    break;
                case 'illegally_unlogged':
                    echo 
                       "<html>
                            <head></head>
                            <body>
                                <script type='text/javascript'>
                                    alert('Sie wurden durch eine unsichere Handlung [z.B. Aufruf einer alten Seite dieser Applikation] ausgeloggt. Bitte loggen Sie sich neu ein, Danke.');
                                </script>
                                <form method='GET'>
                                    Username: <input name='username' value='name'><br>
                                    Passwort: <input name='password' type='password' value='password'><br>
                                    <input type='submit' value='Einloggen'>
                                </form>
                            </body>
                        </html>";
                    break;
                default:
                    echo 
                       "<html>
                            <head></head>
                            <body>
                                <form method='GET'>
                                    Username: <input name='username' value='name'><br>
                                    Passwort: <input name='password' type='password' value='password'><br>
                                    <input type='submit' value='Einloggen'>
                                </form>
                            </body>
                        </html>";
            }
        }
    }

