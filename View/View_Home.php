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
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <table class='mytable'>
                                    <th class='default'>ID</th>
                                    <th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th>
                                    <th class='default'>EMAIL</th>
                                    <th class='default'>TEL</th>
                                    <th class='default'>STATUS</th>
                                    <th class='default'>ERSTER KONTAKT AM</th>
                                    <th class='default'>ERSTER KONTAKT UEBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT UEBER MA</th>
                                    <th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th>
                                    <th class='default'>DATENSATZ BEARBEITEN</th>";
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
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <table class='mytable'>
                                    <form method='GET'>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        $keys = array_keys($actual_dataset);
                                        $key = 0;
                                        foreach ($actual_dataset as $oneentry) {
                                            // switch different input cases generally
                                            echo "<tr>";
                                            echo "<td class='default'>".self::translate($keys[$key])."</td>";
                                            switch ($keys[$key]) {
                                                case 'id':
                                                    echo "<td><input name='".$keys[$key]."' value='".$oneentry."' readonly='readonly'></td>";
                                                    $key++;
                                                    break;
                                                case 'status':
                                                    echo "<td>
                                                            <select name='status'>";
                                                            // switch different input cases for status
                                                            switch ($oneentry) {
                                                                case 'FINAL (G)':
                                                                    echo 
                                                                       "<option selected>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'FINAL (R)':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option selected>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'PENDING (O)':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option selected>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'PENDING (V)':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option selected>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'FORWARDED':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option selected>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'TERMIN':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option selected>TERMIN</option>
                                                                        <option>POOL</option>";
                                                                    break;
                                                                case 'POOL':
                                                                    echo 
                                                                       "<option>FINAL (G)</option>
                                                                        <option>FINAL (R)</option>
                                                                        <option>PENDING (O)</option>
                                                                        <option>PENDING (V)</option>
                                                                        <option>FORWARDED</option>
                                                                        <option>TERMIN</option>
                                                                        <option selected>POOL</option>";
                                                                    break;
                                                            } // switch status
                                                            echo "</select>
                                                        </td>";
                                                        $key++;
                                                        break;
                                                case 'infos':
                                                    echo "<td ><textarea class='infos' name='".$keys[$key]."'>".$oneentry."</textarea></td>";
                                                    break;
                                                    $key++;
                                                default:
                                                    echo "<td><input name='".$keys[$key]."' value='".$oneentry."'></td>";
                                                    $key++;
                                            } // switch key
                                            echo "</tr>";
                                        } // foreach
                                        echo       "<tr>
                                                        <td class='default'>
                                                            <b><font>DATENSATZ SPEICHERN</font></b>
                                                        </td>
                                                        <td>
                                                            <input class='editButton' type='submit' name='saveID' value='".$actual_dataset['id']."'>
                                                        </td>
                                                    </tr>
                                                </form>";
                                    } // foreach
                    echo
                               "</table>
                            </body>
                        </html>";
                    break; // end 'edit'
                case 'insert':
                    // create html
                    self::renderMenu();
                    echo
                       "<html>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <table class='mytable'>
                                    <form method='GET'>
                                        <tr>
                                            <td class='default'>VORNAME</td>
                                            <td><input name='firstname'></td>
                                        </tr>
                                         <tr>
                                            <td class='default'>NAME</td>
                                            <td><input name='name'></td>
                                         <tr>
                                            <td class='default'>EMAIL</td>
                                            <td><input name='email'></td>
                                        </tr>
                                         <tr>
                                            <td class='default'>TEL</td>
                                            <td><input name='telephone'></td>
                                        </tr>
                                         <tr>
                                            <td class='default'>STATUS</td>
                                            <td>
                                                <select name='status'>
                                                    <option>FINAL (G)</option>
                                                    <option>FINAL (R)</option>
                                                    <option>PENDING (O)</option>
                                                    <option>PENDING (V)</option>
                                                    <option>FORWARDED</option>
                                                    <option>TERMIN</option>
                                                    <option>POOL</option>
                                                </select>
                                            </td>
                                        </tr>
                                         <tr>
                                            <td class='default'>ERSTER KONTAKT AM</td>
                                            <td><input name='first_contact_at'></td>
                                        </tr>
                                         <tr>
                                            <td class='default'>ERSTER KONTAKT UEBER PROFIL</td>
                                            <td><input name='first_contact_over_profile'></td>
                                        </tr>
                                         <tr>
                                            <td class='default'>ERSTER KONTAKT UEBER MA</td>
                                            <td><input name='first_contact_from'></td>
                                        </tr>
                                        <tr>
                                            <td class='default'>LETZTES UPDATE</td>
                                            <td><input name='last_update'></td>
                                        </tr>
                                        <tr>
                                        </tr>
                                            <td class='default'>INFOS</td>
                                            <td><textarea class='infos' name='infos'></textarea></td>
                                        </tr>
                                        <tr>
                                        </tr>
                                            <td class='default'><b>DATENSATZ SPEICHERN</b></td>
                                            <td><input class='editButton' type='submit' name='saveDS'></td>
                                    </form>
                                </table>
                            </body>
                        </html>";
                    break; // end 'insert'                
                case 'saved':
                    // create html
                    self::renderMenu();
                    echo
                       "<html>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <link rel='stylesheet' type='text/css' href='View/View.css'>
                            </head>
                            <body>
                                <script type='text/javascript'>
                                    alert('Ihr Datensatz wurde gespeichert.');
                                </script>
                                <table class='mytable'>
                                    <th class='default'>ID</th>
                                    <th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th>
                                    <th class='default'>EMAIL</th>
                                    <th class='default'>TEL</th>
                                    <th class='default'>STATUS</th>
                                    <th class='default'>ERSTER KONTAKT AM</th>
                                    <th class='default'>ERSTER KONTAKT UEBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT UEBER MA</th>
                                    <th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th>
                                    <th class='default'>DATENSATZ BEARBEITEN</th>";
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
        
        // translate english terms into german
        public function translate($term) {
            switch ($term) {
                case 'id':
                    return 'ID';
                case 'firstname':
                    return 'VORNAME';
                case 'name':
                    return 'NAME';
                case 'email':
                    return 'EMAIL';
                case 'telephone':
                    return 'TELEFON';
                case 'status':
                    return 'STATUS';
                case 'first_contact_at':
                    return 'ERSTER KONTAKT AM';
                case 'first_contact_over_profile':
                    return 'ERSTER KONTAKT UEBER PROFIL';
                case 'first_contact_from':
                    return 'ERSTER KONTAKT UEBER MA';
                case 'last_update':
                    return 'LETZTES UPDATE';
                case 'infos':
                    return 'INFOS';
            }
        }
    }
    
    


?>