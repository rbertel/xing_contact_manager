<?php
    
    
    class View_Home {
        
        
        /* renders the output, param: mode_2 -> status what have to render (all datasets, one ...)
        */
        public function display($mode_2, $data) {
            
            switch ($mode_2) {
                
                case "show":
                    // create html
                    echo     "<html>
                                <head>
                                    <link rel='stylesheet' type='text/css' href='View/View.css'>
                                </head>
                                <body>
                                    <table class='mytable'>
                                        <th class='myth'>ID</th><th class='myth'>VORNAME</th><th class='myth'>NAME</th><th class='myth'>EMAIL</th><th class='myth'>TEL</th><th class='myth'>STATUS</th><th class='myth'>ERSTER KONTAKT AM</th><th class='myth'>ERSTER KONTAKT UEBER PROFIL</th><th class='myth'>ERSTER KONTAKT UEBER MA</th><th class='myth'>LETZTES UPDATE</th><th class='myth'>INFOS</th><th class='myth'></th>";
                                    // iterate trough data and print on screen
                                    foreach($data as $actual_dataset) {
                                        echo "<tr>";
                                        foreach ($actual_dataset as $oneentry) {
                                            // choose background in fact of status
                                            switch ($actual_dataset['status']) {
                                                case 'FINAL (R)':
                                                    echo "<td class='mytd' bgcolor='red'>".$oneentry."</td>";
                                                    break;
                                                case 'FINAL (G)':
                                                    echo "<td class='mytd' bgcolor='lime'>".$oneentry."</td>";
                                                    break;                                               
                                                case 'PENDING (O)':
                                                    echo "<td class='mytd' bgcolor='orange'>".$oneentry."</td>";
                                                    break;
                                                case 'PENDING (V)':
                                                    echo "<td class='mytd' bgcolor='plum'>".$oneentry."</td>";
                                                    break;
                                                case 'FORWARDED':
                                                    echo "<td class='mytd' bgcolor='yellow'>".$oneentry."</td>";
                                                    break;
                                                case 'POOL':
                                                    echo "<td class='mytd' bgcolor='cyan'>".$oneentry."</td>";
                                                    break;
                                                case 'TERMIN':
                                                    echo "<td class='mytd' bgcolor='greenyellow'>".$oneentry."</td>";
                                                    break;
                                                default:
                                                    echo "<td class='mytd'>".$oneentry."</td>";
                                            }
                                        }
                                        echo   "<td class='mytd'><form method='GET'>
                                                    <input id=".$actual_dataset['id']." type='submit' name='update' value='Diesen Datensatz bearbeiten'>
                                                </form></td>";
                                        echo    "</tr>";
                                    }
                    echo           "</table>
                                </body>
                            </html>"; 
                default:
                    echo   "<html>
                                <body>
                                    <form method='GET'>
                                        <input type='submit' name='showall' value='Alle Datensaetze anzeigen'>
                                        <input type='submit' name='deleteview' value='Anzeigefeld leeren'><br>
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
    }
    
    


?>