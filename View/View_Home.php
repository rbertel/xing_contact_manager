<?php
        
    class View_Home {
        
        /* renders the output, param: mode_2 -> status what have to render (all datasets, one ...)
        */
        public function display($mode_2, $data) {
            
            echo $mode_2;
            // render different outputs
            switch ($mode_2) {
                case 'show':
                    // create html
                    self::renderMenu();
                    echo
                       "<html>
                            <head>
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <table class='mytable'>
                                    <th class='default'>ID</th><th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th><th class='default'>EMAIL</th>
                                    <th class='default'>TEL</th><th class='default'>STATUS</th>
                                    <th class='default'>ERSTER KONTAKT AM</th><th class='default'>ERSTER KONTAKT UEBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT UEBER MA</th><th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th><th class='default'>DATENSATZ BEARBEITEN</th>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        echo "<tr>";
                                        foreach ($actual_dataset as $oneentry) {
                                            // choose background in fact of status
                                            switch ($actual_dataset['status']) {
                                                case 'FINAL (R)':
                                                    echo "<td bgcolor='red'>".$oneentry."</td>";
                                                    break;
                                                case 'FINAL (G)':
                                                    echo "<td bgcolor='lime'>".$oneentry."</td>";
                                                    break;                                               
                                                case 'PENDING (O)':
                                                    echo "<td bgcolor='orange'>".$oneentry."</td>";
                                                    break;
                                                case 'PENDING (V)':
                                                    echo "<td bgcolor='plum'>".$oneentry."</td>";
                                                    break;
                                                case 'FORWARDED':
                                                    echo "<td bgcolor='yellow'>".$oneentry."</td>";
                                                    break;
                                                case 'POOL':
                                                    echo "<td bgcolor='cyan'>".$oneentry."</td>";
                                                    break;
                                                case 'TERMIN':
                                                    echo "<td bgcolor='greenyellow'>".$oneentry."</td>";
                                                    break;
                                                default:
                                                    echo "<td class='default'>".$oneentry."</td>";
                                            }
                                        }
                                        echo   "<td class='default'><form method='GET'>
                                                    <input class='editButton' type='submit' name='editID' value='".$actual_dataset['id']."'>
                                                </form></td>";
                                        echo    "</tr>";
                                    }
                    echo
                               "</table>
                            </body>
                        </html>"; 
                    break; // end 'show'
                case 'edit':
                    // create html
                    self::renderMenu();
                    echo
                       "<html>
                            <head>
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <table class='mytable'>
                                    <th class='default'>ID</th><th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th><th class='default'>EMAIL</th>
                                    <th class='default'>TEL</th><th class='default'>STATUS</th>
                                    <th class='default'>ERSTER KONTAKT AM</th><th class='default'>ERSTER KONTAKT UEBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT UEBER MA</th><th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th><th class='default'>DATENSATZ SPEICHERN</th>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        echo "<tr>";
                                        $keys = array_keys($actual_dataset);
                                        $key = 0;
                                        foreach ($actual_dataset as $oneentry) {
                                            switch ($keys[$key]) {
                                                case 'id':
                                                    echo "<td><form method='GET'>".$oneentry."</td>";
                                                    $key++;
                                                    break;
                                                default:
                                                    echo "<td><form method='GET'><input name='".$keys[$key]."' value='".$oneentry."'></td>";
                                                    $key++;
                                            }
                                        }
                                        echo       "<td class='default'>
                                                        <form method='GET'>
                                                            <input class='editButton' type='submit' name='saveID' value='".$actual_dataset['id']."'>
                                                        </form>
                                                    </td>";
                                        echo   "</tr>";
                                    }
                    echo
                               "</table>
                            </body>
                        </html>";
                    break; // end 'edit'
                case 'saved':
                    // create html
                    self::renderMenu();
                    echo
                       "<html>
                            <head>
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <script type='text/javascript'>
                                    alert('Ihr Datensatz wurde gespeichert.');
                                </script>
                                <table class='mytable'>
                                    <th class='default'>ID</th><th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th><th class='default'>EMAIL</th>
                                    <th class='default'>TEL</th><th class='default'>STATUS</th>
                                    <th class='default'>ERSTER KONTAKT AM</th><th class='default'>ERSTER KONTAKT UEBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT UEBER MA</th><th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th><th class='default'>DATENSATZ BEARBEITEN</th>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        echo "<tr>";
                                        foreach ($actual_dataset as $oneentry) {
                                            // choose background in fact of status
                                            switch ($actual_dataset['status']) {
                                                case 'FINAL (R)':
                                                    echo "<td bgcolor='red'>".$oneentry."</td>";
                                                    break;
                                                case 'FINAL (G)':
                                                    echo "<td bgcolor='lime'>".$oneentry."</td>";
                                                    break;                                               
                                                case 'PENDING (O)':
                                                    echo "<td bgcolor='orange'>".$oneentry."</td>";
                                                    break;
                                                case 'PENDING (V)':
                                                    echo "<td bgcolor='plum'>".$oneentry."</td>";
                                                    break;
                                                case 'FORWARDED':
                                                    echo "<td bgcolor='yellow'>".$oneentry."</td>";
                                                    break;
                                                case 'POOL':
                                                    echo "<td bgcolor='cyan'>".$oneentry."</td>";
                                                    break;
                                                case 'TERMIN':
                                                    echo "<td bgcolor='greenyellow'>".$oneentry."</td>";
                                                    break;
                                                default:
                                                    echo "<td class='default'>".$oneentry."</td>";
                                            }
                                        }
                                        echo   "<td class='default'><form method='GET'>
                                                    <input class='editButton' type='submit' name='editID' value='".$actual_dataset['id']."'>
                                                </form></td>";
                                        echo    "</tr>";
                                    }
                    echo
                               "</table>
                            </body>
                        </html>";
                    break; // end saved
                default:
                    self::renderMenu();
            } // end switch
        }
        
        // renders the menu
        public function renderMenu() {
            echo
               "<html>
                    <body>
                        <form method='GET'>
                            <input type='submit' name='showall' value='Alle Datensaetze anzeigen'>
                            <input type='submit' name='deleteview' value='Anzeigefeld leeren'>
                            <input type='submit' name='insert' value='Neuen Datensatz erstellen'><br>
                        </form>
                         <br>
                        <form method='GET'>
                            <select name='searchtype' size='1'>
                                <option>name</option>
                                <option>status</option>
                            </select>
                            <input name='searchterm'>
                            <input type='submit' name='search' value='Datensatz suchen und anzeigen'><br>
                        </form>
                    </body>
                </html>";  
            }
        
    }
    
    


?>