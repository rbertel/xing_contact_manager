<?php

    class View_Login {
                
        public function display() {
            
            echo 
                "<html>
                    <head></head>
                    <body>
                        <form method='GET'>
                            Username: <input name='username' value='name'><br>
                            Passwort: <input name='password' type='password' value='password'><br>
                            <input type='submit' value='Einloggen'>
                            <input type='submit' name='logout' value='Ausloggen'>
                        </form>
                    </body>
                </html>";
        }
    }

