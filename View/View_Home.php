<?php
        
    class View_Home {
        
        //******************************************************************************************
        
        /* renders the output, param: mode_2 -> status what have to render (all datasets, one ...)
        */
        public function display($mode_2, $data) {
            
            // create 2 datepickers with JQUERY for mode_2 -> edit or insert
            echo   "<script type='text/javascript'>
                        $(function() {
                            $( '#first_contact_at' ).datepicker({ dateFormat: 'yy-mm-dd' });
                        });
                        $(function() {
                            $( '#last_update' ).datepicker({ dateFormat: 'yy-mm-dd' });
                        });
                        $(document).ready(function() 
                        { 
                            $('#tableToSort').tablesorter(); 
                        });
                    </script>";
            // render different outputs
            switch ($mode_2) {
                case 'show':
                    // create html
                    self::renderMenu();
                    // if no data is there to show, render little info and break
                    if ($data == NULL) {
                        echo "<font size='4'>Kein Datensatz gefunden, bitte erneut versuchen</font>
                        </body></html>";
                        break;
                    }
                    echo "<br>";
                    self::renderShowTable($data);
                    break; // end 'show'
                case 'edit':
                    // create html
                    self::renderMenu();
                    echo "<br><font color='red' size='3'>Datensatz bearbeiten:</font><br>";
                    self::renderEditTable($data);                
                    echo "</body></html>";
                    break; // end 'edit'
                case 'editFailed':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Zum Bearbeiten bitte nur einen Datensatz auswählen</font><br>";
                    self::renderShowTable($data);                
                    echo "</body></html>";
                    break; // end 'editfailed'
                case 'nothingChoosed':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Bitte Datensatz auswählen</font><br>";
                    self::renderShowTable($data);                
                    echo "</body></html>";
                    break; // end 'editfailed
                case 'insert':
                    // create html
                    self::renderMenu();
                    echo "<br><font color='red' size='3'>Datensatz einfügen:</font><br>";
                    self::renderInsertTable();
                    echo "</body></html>";
                    break; // end 'insert'
                case 'duplicated':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Der Datensatz ist schon vorhanden</font><br>";
                    self::renderInsertTable();
                    echo "</body></html>";
                    break; // end 'insert'
                case 'supposedDuplicated':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Es wurden vermeintliche Duplikate gefunden</font><br>";
                    self::renderValidateTable($data);
                    echo "</body></html>";
                    break; // end 'duplicated'
                case 'saved':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Der Datensatz wurde gespeichert</font><br>";
                    self::renderShowTable($data);
                    break; // end saved
                case 'delete':
                    // create html
                    self::renderMenu();
                    echo "<br><font color='red' size='3'>Sie beabsichtigen folgende Datensätze zu löschen:</font><br>
                                <font size='2'>(Zum Löschen bitte unten bestätigen)</font><br><br>";
                    self::renderDeleteTable($data);
                    echo "</body></html>";
                    break; // end 'show'
                case 'deleted':
                    // create html
                    self::renderMenu();
                    echo "<font size='2' color='red'><b>! </b>Der Datensatz wurde gelöscht</font><br>";
                    self::renderShowTable($data);
                    break; // end deleted                
                default:
                    self::renderMenu();
                    echo "</body></html>";
            } // end switch
        }
        
        //******************************************************************************************
        //******************************************************************************************
        //******************************************************************************************
        
        // THE RENDER METHODS
        
        //**************************************************************************************
        //******************************************************************************************
        //******************************************************************************************
        
                
        //******************************************************************************************
        
        /*
         
        renderShowTable()
        renders the swowtable with content
        expect the data
        
        */    
        public function renderShowTable($data) {
            echo "<form method='GET'><div class='actionTableCont'><table class='mytable'>
                                    <th class='default'>X</th>
                                    <th class='default'>ID</th>
                                    <th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th>
                                    <th class='default'>TÄTIGKEIT</th>
                                    <th class='default'>STATUS</th>
                                    <th class='default'>XING PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT AM</th>
                                    <th class='default'>ERSTER KONTAKT ÜBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT ÜBER MA</th>
                                    <th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        echo "<tr>";
                                        echo  "<td class='default'>
                                                    <input type='checkbox' name='choosed[]' value='".$actual_dataset['id']."'>
                                                </td>";
                                        foreach ($actual_dataset as $oneentry) {
                                            // choose background in fact of status
                                            switch ($actual_dataset['status']) {
                                                case 'FINAL (R)':
                                                    echo "<td class='mycell' bgcolor='red'>".$oneentry."</td>";
                                                    break;
                                                case 'FINAL (G)':
                                                    echo "<td class='mycell' bgcolor='lime'>".$oneentry."</td>";
                                                    break;                                               
                                                case 'PENDING (O)':
                                                    echo "<td class='mycell' bgcolor='orange'>".$oneentry."</td>";
                                                    break;
                                                case 'PENDING (V)':
                                                    echo "<td class='mycell' bgcolor='plum'>".$oneentry."</td>";
                                                    break;
                                                case 'FORWARDED':
                                                    echo "<td class='mycell' bgcolor='yellow'>".$oneentry."</td>";
                                                    break;
                                                case 'POOL':
                                                    echo "<td class='mycell' bgcolor='cyan'>".$oneentry."</td>";
                                                    break;
                                                case 'TERMIN':
                                                    echo "<td class='mycell' bgcolor='greenyellow'>".$oneentry."</td>";
                                                    break;
                                                default:
                                                    echo "<td class='default'>".$oneentry."</td>";
                                            }
                                        }
                                        echo    "</tr>";
                                    }
                    echo
                               "</table>
                                <div class='arrow'><img src='eckpfeil.gif'></div>
                                <div class='actionFormLeft'>
                                    <select class='action' name='action'>
                                        <option></option>
                                        <option>Bearbeiten</option>
                                        <option>Entfernen</option>
                                    </select>
                                    <input type='submit' class='actionButton' name='editdelete' value='Go'>
                                </div></div></form>
                            </body>
                        </html>";
            
            
        }
        
        //******************************************************************************************
        
        /*
         
        renderDeleteTable()
        renders the swowtable with content
        expect the data
        
        */    
        public function renderDeleteTable($data) {
            
            echo "<form method='GET'><div class='actionTableCont'><table class='mytable'>
            <th class='default'>ID</th>
            <th class='default'>VORNAME</th>
            <th class='default'>NAME</th>
            <th class='default'>TÄTIGKEIT</th>
            <th class='default'>STATUS</th>
            <th class='default'>XING PROFIL</th>
            <th class='default'>ERSTER KONTAKT AM</th>
            <th class='default'>ERSTER KONTAKT ÜBER PROFIL</th>
            <th class='default'>ERSTER KONTAKT ÜBER MA</th>
            <th class='default'>LETZTES UPDATE</th>
            <th class='default'>INFOS</th>";
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
                echo    "</tr>";
            }
            echo
            "</table>
            <div class='actionForm'>
            <input type='submit' class='backButton' name='back' value='Zurück'>                      
            <input type='submit' name='confirmDelIDNEU' value='Löschen'></div></div>
            </form>";
        }
        
        //******************************************************************************************
        
        /*
         
        renderEditTable()
        
        */    
        public function renderEditTable($data) {
        
            // iterate trough data and print on screen
            foreach($data as $actual_dataset) {
                $keys = array_keys($actual_dataset);
                $key = 0;
                echo "<table class='edittable'>";
                echo "<form method='GET'>";
                foreach ($actual_dataset as $oneentry) {
                    // switch different input cases generally
                    echo "<tr>";
                    echo "<td  class='editinsertLeftAlign'>".self::translate($keys[$key])."</td>";
                    switch ($keys[$key]) {
                        case 'id':
                            echo "<td><input name='".$keys[$key]."' value='".$oneentry."' readonly='readonly'></td>";
                            $key++;
                            break;
                        case 'job':
                            echo "<td>
                            <select name='job'>";
                            // switch different input cases for status
                            switch ($oneentry) {
                                case 'undefined':
                                    echo "<option selected>undefined</option><option>SysAd</option><option>PHP</option><option>Frontend</option><option>Java</option><option>DWH/BI</option>";
                                    break;
                                case 'SysAd':
                                    echo "<option>undefined</option><option selected>SysAd</option><option>PHP</option><option>Frontend</option><option>Java</option><option>DWH/BI</option>";
                                    break;
                                case 'PHP':
                                    echo "<option>undefined</option><option>SysAd</option><option selected>PHP</option><option>Frontend</option><option>Java</option><option>DWH/BI</option>";
                                    break;
                                case 'Frontend':
                                    echo "<option>undefined</option><option>SysAd</option><option>PHP</option><option selected>Frontend</option><option>Java</option><option>DWH/BI</option>";
                                    break;
                                case 'Java':
                                    echo "<option>undefined</option><option>SysAd</option><option>PHP</option><option>Frontend</option><option selected>Java</option><option>DWH/BI</option>";
                                    break;
                                case 'DWH/BI':
                                    echo 
                                       "<option>undefined</option><option selected>SysAd</option><option>PHP</option><option>Frontend</option><option>Java</option><option selected>DWH/BI</option>";
                                    break;
                            } // switch status
                            echo "</select>
                            </td>";
                            $key++;
                            break; 
                        case 'status':
                            echo "<td>
                            <select name='status'>";
                            // switch different input cases for status
                            switch ($oneentry) {
                                case 'undefined':
                                    echo "<option selected>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'FINAL (G)':
                                    echo "<option>undefined</option><option selected>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'FINAL (R)':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option selected>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'PENDING (O)':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option selected>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'PENDING (V)':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option selected>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'FORWARDED':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option selected>FORWARDED</option><option>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'TERMIN':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option selected>TERMIN</option><option>POOL</option>";
                                    break;
                                case 'POOL':
                                    echo "<option>undefined</option><option>FINAL (G)</option><option>FINAL (R)</option><option>PENDING (O)</option><option>PENDING (V)</option><option>FORWARDED</option><option>TERMIN</option><option selected>POOL</option>";
                                    break;
                            } // switch status
                            echo "</select>
                            </td>";
                            $key++;
                            break;
                        case 'first_contact_at':
                            echo "<td><input id='first_contact_at' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                            break;
                        case 'last_update':
                            echo "<td><input id='last_update' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                            break;
                        case 'infos':
                            echo "<td><textarea class='infos' name='".$keys[$key]."'>".$oneentry."</textarea></td>";
                            break;
                            $key++;
                        default:
                            echo "<td><input name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                    } // switch key
                    echo "</tr>";
                } // foreach
                echo "<tr>
                        <td class='editinsertLeftAlign'><b>DATENSATZ SPEICHERN<b></td>
                        <td>
                          <input class='editButton' type='submit' name='saveEdited' value='".$actual_dataset['id']."'></form>
                        </td>  ";
                echo "</table><br>";
            } // foreach
            echo "<form method='GET'><input class='backButton' type='submit' name='back' value='Zurück'></form>";
        }
        
        //******************************************************************************************
        
        // renders the Insert Table        
        
        public function renderInsertTable() {
            echo
            "<table class='edittable'>
                <form method='GET'>
                    <tr>
                        <td class='editinsertLeftAlign'>VORNAME</td>
                        <td><input name='firstname'></td>
                    </tr>
                     <tr>
                        <td class='editinsertLeftAlign'>NAME</td>
                        <td><input name='name'></td>
                     <tr>
                        <td class='editinsertLeftAlign'>TÄTIGKEIT</td>
                        <td>
                            <select name='job'>
                                <option>undefined</option>
                                <option>SysAd</option>
                                <option>PHP</option>
                                <option>Frontend</option>
                                <option>Java</option>
                                <option>DWH/BI</option>
                            </select>
                        </td>
                    </tr>
                     <tr>
                        <td class='editinsertLeftAlign'>STATUS</td>
                        <td>
                            <select name='status'>
                                <option>undefined</option>    
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
                        <td class='editinsertLeftAlign'>XING PROFIL</td>
                        <td><input id='xing_profile' name='xing_profile'></td>
                    </tr>
                     <tr>
                        <td class='editinsertLeftAlign'>ERSTER KONTAKT AM</td>
                        <td><input id='first_contact_at' name='first_contact_at'></td>
                    </tr>
                     <tr>
                        <td class='editinsertLeftAlign'>ERSTER KONTAKT ÜBER PROFIL</td>
                        <td><input name='first_contact_over_profile'></td>
                    </tr>
                     <tr>
                        <td class='editinsertLeftAlign'>ERSTER KONTAKT ÜBER MA</td>
                        <td><input name='first_contact_from'></td>
                    </tr>
                    <tr>
                        <td class='editinsertLeftAlign'>LETZTES UPDATE</td>
                        <td><input id='last_update' name='last_update'></td>
                    </tr>
                    <tr>
                    </tr>
                        <td class='editinsertLeftAlign'>INFOS</td>
                        <td><textarea class='infos' name='infos'></textarea></td>
                    </tr>
                    <tr>
                    </tr>
                        <td class='editinsertLeftAlign'><b>DATENSATZ SPEICHERN</b></td>
                        <td><input class='editButton' type='submit' name='saveNew'></td>
                        </form>
                </table><br><form method='GET'><input class='backButton' type='submit' name='back' value='Zurück'></form>";
        }
        
                //******************************************************************************************
        
        // renders the Insert Table        
        
        public function renderValidateTable($data) {
            echo "<form method='GET'><div class='actionTableCont'><table class='mytable'>
                                    <th class='default'>X</th>
                                    <th class='default'>ID</th>
                                    <th class='default'>VORNAME</th>
                                    <th class='default'>NAME</th>
                                    <th class='default'>TÄTIGKEIT</th>
                                    <th class='default'>STATUS</th>
                                    <th class='default'>XING PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT AM</th>
                                    <th class='default'>ERSTER KONTAKT ÜBER PROFIL</th>
                                    <th class='default'>ERSTER KONTAKT ÜBER MA</th>
                                    <th class='default'>LETZTES UPDATE</th>
                                    <th class='default'>INFOS</th>";
                                    // iterate trough data and print on screen
            echo "<tr><td class='newData'><font size='1' color='#873549'>X</font></td>
                      <td class='newData'><font color='white'>-> Neuer Datensatz</font></td>";
            foreach ($data[0] as $oneentry) {
                echo "<td class='newData'><font color='white'>".$oneentry."</font></td>";
            }
                                    foreach($data[1] as $actual_dataset) {
                                        echo "<tr>";
                                         echo "<td class='default'>
                                                <input type='checkbox' name='choosed[]' value='".$actual_dataset['id']."'>
                                               </td>";
                                        foreach ($actual_dataset as $oneentry) {
                                            // choose background in fact of status
                                            switch ($actual_dataset['status']) {
                                                case 'FINAL (R)':
                                                    echo "<td class='mycell' bgcolor='red'>".$oneentry."</td>";
                                                    break;
                                                case 'FINAL (G)':
                                                    echo "<td class='mycell' bgcolor='lime'>".$oneentry."</td>";
                                                    break;                                               
                                                case 'PENDING (O)':
                                                    echo "<td class='mycell' bgcolor='orange'>".$oneentry."</td>";
                                                    break;
                                                case 'PENDING (V)':
                                                    echo "<td class='mycell' bgcolor='plum'>".$oneentry."</td>";
                                                    break;
                                                case 'FORWARDED':
                                                    echo "<td class='mycell' bgcolor='yellow'>".$oneentry."</td>";
                                                    break;
                                                case 'POOL':
                                                    echo "<td class='mycell' bgcolor='cyan'>".$oneentry."</td>";
                                                    break;
                                                case 'TERMIN':
                                                    echo "<td class='mycell' bgcolor='greenyellow'>".$oneentry."</td>";
                                                    break;
                                                default:
                                                    echo "<td class='default' class='default'>".$oneentry."</td>";
                                            }
                                        }
                                        echo    "</tr>";
                                    }
                    echo
                               "</table>
                                    <div class='arrow'><img src='eckpfeil.gif'></div>
                                    <div class='actionFormLeft'>
                                        <input type='submit' class='actionButton' name='editdelete' value='Bearbeiten'>
                                    </div>
                                    <div class='actionFormRight'>
                                        <font size='2' color='red'>Neuen Datensatz</font>
                                        <input type='submit' class='actionButton' name='confirmInsert' value='Speichern'>
                                    </div></div></form>
                            </body>
                        </html>"; 
        }
        
        //******************************************************************************************
        
        // renders the main menu
        public function renderMenu() {
            echo
                       "  <form method='GET'>
                            <input class='menu' type='submit' name='showall' value='Alle Datensätze anzeigen'>
                            <input class='menu' type='submit' name='insert' value='Neuen Datensatz erstellen'>
                            <input class='menu' type='submit' name='deleteview' value='Anzeigefeld leeren'><br>
                        </form><form method='GET'>
                            <font size='2'>Name: </font><input name='searchterm_name'>
                            <font size='2'>Tätigkeit: </font><select class='search' name='searchterm_job' size='1'>
                                <option></option>
                                <option>undefined</option>
                                <option>SysAd</option>
                                <option>PHP</option>
                                <option>Frontend</option>
                                <option>Java</option>
                                <option>DWH/BI</option>
                            </select>
                            <font size='2'>Status: </font><select class='search' name='searchterm_status' size='1'>
                                <option></option>
                                <option>undefined</option>
                                <option>FINAL</option>
                                <option>FINAL (R)</option>
                                <option>FINAL (G)</option>
                                <option>PENDING</option>
                                <option>PENDING (O)</option>
                                <option>PENDING (V)</option>
                                <option>FORWARDED</option>
                                <option>TERMIN</option>
                                <option>POOL</option>
                            </select>
                            <input type='submit' name='search' value='Suchen'><br>
                        </form>";  
        }
        
        //******************************************************************************************
        
        // translate english terms into german
        public function translate($term) {
            switch ($term) {
                case 'id':
                    return 'ID';
                case 'firstname':
                    return 'VORNAME';
                case 'name':
                    return 'NAME';
                case 'job':
                    return 'TÄTIGKEIT';
                case 'status':
                    return 'STATUS';
                case 'xing_profile':
                    return 'XING PROFIL';
                case 'first_contact_at':
                    return 'ERSTER KONTAKT AM';
                case 'first_contact_over_profile':
                    return 'ERSTER KONTAKT ÜBER PROFIL';
                case 'first_contact_from':
                    return 'ERSTER KONTAKT ÜBER MA';
                case 'last_update':
                    return 'LETZTES UPDATE';
                case 'infos':
                    return 'INFOS';
            }
        }
    }
    
    


?>