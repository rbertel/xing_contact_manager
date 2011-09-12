<?php
        
    class View_Home {
        
        //******************************************************************************************
        
        /*
         
          function display()
          renders the output, param: mode_2 -> status what have to render (all datasets, one ...)
        
        */
        public function display($mode_2, $data, $messageFromDb) {
            
            // create 2 datepickers with JQUERY for mode_2 -> edit or insert
            // create function for mark or unmark the datasets
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
                        
                        function checkedall() {
                            for (var i = 0; i < document.forms[3].elements.length; i++) {
                                if (document.getElementById('checkall').checked == true) {
                                    document.forms[3].elements[i].checked = true;
                                } else {
                                    document.forms[3].elements[i].checked = false;
                                }
                            }
                        }
            </script>";
            // render different outputs
            switch ($mode_2) {
                // user want to see all datasets
                case 'show':
                    self::renderMainMenu();
                    // if no data is there to show -->
                    if ($data == NULL) {
                        if ($messageFromDb == NULL) {
                            self::renderTableHeader();
                            echo "</body></html>";
                            break;
                        } else {
                            echo "<div class='message'>
                                    <div class='messageLeft'>
                                        <font size='2' color='red'><b>! *** </b>".$messageFromDb."</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                            self::renderTableHeader();
                            echo "</body></html>";
                            break; 
                        }
                    } elseif ($messageFromDb == NULL) {
                        self::renderShowTable($data);
                        echo "</body></html>";
                        break;
                    } 
                    echo "<div class='message'>
                            <div class='messageLeft'>
                            <font size='2' color='red'><b>! *** </b>".$messageFromDb."</font>
                            </div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);
                    echo "</body></html>";
                    break; // end 'show'                    
                
                // user want to edit dataset
                case 'edit':
                    // create html
                    self::renderbackButton();
                    self::renderEditTable($data);                
                    echo "</body></html>";
                    break; // end 'edit'
                
                // user choosed more than one datset to edit
                case 'toMuchChoosed':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Zum Bearbeiten bitte nur einen Datensatz auswählen</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);                
                    echo "</body></html>";
                    break; // end 'editfailed'
                
                // user choosed nothing to edit, delete or export
                case 'nothingChoosed':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Bitte Datensatz auswählen</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);                
                    echo "</body></html>";
                    break; // end 'nothingChoosed
                
                // user choosed more than one dataset to edit in validate table
                case 'toMuchChoosedWhileValidate':
                    // create html
                    self::renderbackButton();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Es wurden vermeintliche Duplikate gefunden  </font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Zum Bearbeiten bitte nur einen Datensatz auswählen</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderValidateTable($data);                
                    echo "</body></html>";
                    break; // end 'editfailedWhileValidate'
                
                // user choosed nothing to edit in validate table
                case 'nothingChoosedWhileValidate':
                    // create html
                    self::renderbackButton();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Es wurden vermeintliche Duplikate gefunden  </font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Bitte Datensatz auswählen</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderValidateTable($data);                
                    echo "</body></html>";
                    break; // end 'nothingChoosedWhileValidate
                
                // user choosed no action in "select" formular
                case 'noActionChoosed':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Bitte Aktion auswählen</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);                
                    echo "</body></html>";
                    break; // end 'noActionChoosed
                
                // user want to insert new dataset
                case 'insert':
                    // create html
                    self::renderbackButton();
                    self::renderInsertTable();
                    echo "</body></html>";
                    break; // end 'insert'
                
                // user want to insert duplicated dataset
                case 'duplicated':
                    // create html
                    self::renderbackButton();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Ein Datensatz mit gleichem Xing Profil ist schon vorhanden</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderInsertTable();
                    echo "</body></html>";
                    break; // end 'duplicated'
                
                // user want to insert supposed duplicated dataset
                case 'supposedDuplicated':
                    // create html
                    self::renderbackButton();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Es wurden vermeintliche Duplikate gefunden</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderValidateTable($data);
                    echo "</body></html>";
                    break; // end 'duplicated'
                
                // user want to save edited or new dataset
                case 'saved':
                    self::renderMainMenu();
                    // create html
                    if ($data == NULL) {
                        echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>".$messageFromDb."</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                        self::renderTableHeader();
                        echo "</body></html>";
                        break;
                    }
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>".$messageFromDb."</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);
                    echo "</body></html>";
                    break; // end saved
                
                // user want to delete dataset(s)
                case 'delete':
                    // create html
                    self::renderbackButton();
                    echo "<div class='message'><div class='messageLeft'><font color='red' size='2'><b>! *** </b>Sie beabsichtigen folgende Datensätze zu löschen:</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderDeleteTable($data);
                    echo "</body></html>";
                    break; // end 'delete'
                
                // user has delete dataset(s)
                case 'deleted':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>".$messageFromDb."</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);
                    echo "</body></html>";
                    break; // end deleted
                
                // user want to export dataset(s) to csv file
                case 'export':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>Die Datensätze wurden exportiert</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderShowTable($data);
                    break; // end export
                
                // user pressed "search" in default modus (empty table)
                case 'defaultEmptyFields':
                    // create html
                    self::renderMainMenu();
                    echo "<div class='message'><div class='messageLeft'><font size='2' color='red'><b>! *** </b>".$messageFromDb."</font></div><div class='messageRight'><font size='2' color='red'><b>*** !</b></font></div></div>";
                    self::renderTableHeader();
                    echo "</body></html>";
                    break; // end export
                
                // default
                default:
                    self::renderMainMenu();
                    self::renderTableHeader();
                    echo "</body></html>";
            } // end switch
        }
        
        //******************************************************************************************
        //******************************************************************************************
        //******************************************************************************************
        
        // THE RENDER METHODS
        
        //******************************************************************************************
        //******************************************************************************************
        //******************************************************************************************
        
                
        //******************************************************************************************
        
        /*
         
        function renderShowTable()
        renders the swowtable with content
        expects the data
        
        */    
        public function renderShowTable($data) {
            // render tableheader
            echo   "<div id='id_hardTableCont' class='hardTableCont' style='height:600px'>
                        <form method='GET'>
                            <div id='id_flexibleTableCont' class='flexibleTableCont'>
                                <table id='mytable' class='mytable' cellspacing='1'>
                                    <th class='black'><font color='white'>X</font></th>
                                    <th class='black'><font color='white'>ID</font></th>
                                    <th class='black'><font color='white'>VORNAME</font></th>
                                    <th class='black'><font color='white'>NAME</font></th>
                                    <th class='black'><font color='white'>TÄTIGKEIT</font></th>
                                    <th class='black'><font color='white'>STATUS</font></th>
                                    <th class='black'><font color='white'>XING PROFIL</font></th>
                                    <th class='black'><font color='white'>ERSTER KONTAKT AM</font></th>
                                    <th class='black'><font color='white'>ERSTER KONTAKT ÜBER PROFIL</font></th>
                                    <th class='black'><font color='white'>ERSTER KONTAKT ÜBER MA</font></th>
                                    <th class='black'><font color='white'>LETZTES UPDATE</font></th>
                                    <th class='black'><font color='white'>INFOS</font></th>";
            // background of tablerow
            $row_background = "normal_white"; 
            // iterate trough data and print on screen
            foreach($data as $actual_dataset) {
                // get arraykeys for checkout actual status
                $keys = array_keys($actual_dataset);
                $key = 0;
                // alternate background of tablerow
                if ($row_background == "normal_white") {
                    $row_background = "normal_grey";
                } else {
                    $row_background = "normal_white";
                }
                // render checkbox
                echo "<tr>";
                echo  "<td class='default'>
                            <input type='checkbox' name='choosed[]' value='".$actual_dataset['id']."'>
                        </td>";
                // RENDER THE BIG REST
                foreach ($actual_dataset as $oneentry) {
                    switch ($keys[$key]) {
                        case 'status':
                            // choose background in fact of status
                            switch ($actual_dataset['status']) {
                                case 'FINAL (R)':
                                    echo "<td class='mycell' bgcolor='tomato'>".$oneentry."</td>";
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
                                    echo "<td class='mycell' bgcolor='#FF6'>".$oneentry."</td>";
                                    break;
                                case 'POOL':
                                    echo "<td class='mycell' bgcolor='aqua'>".$oneentry."</td>";
                                    break;
                                case 'TERMIN':
                                    echo "<td class='mycell' bgcolor='greenyellow'>".$oneentry."</td>";
                                    break;
                                default:
                                    echo "<td class='".$row_background."'>".$oneentry."</td>";
                            }
                            break;
                        case 'xing_profile':
                            echo "<td class='".$row_background."'><a href='".$oneentry."'>".$oneentry."</a></td>";
                            break;
                        default:
                            echo "<td class='".$row_background."'>".$oneentry."</td>";
                    }
                    $key++;
                }
                echo    "</tr>";
            }
            echo                   "<tr>";
                echo                   "<td class='lastrow'>
                                            <input id='checkall' type='checkbox' name='chooseAll' onclick='checkedall()'>
                                        </td>";
           // alternate background of tablerow for last row 
            if ($row_background == "normal_white") {
                $row_background = "normal_grey";
            } else {
                $row_background = "normal_white";
            }
            echo                   "<td class='lastrow' colspan='12'>Alle auswählen</td></tr>";
            echo               "</table>
                            </div> <!-- END flexibleTableCont div -->
                            <div class='actionForm'>
                                <div class='arrow'>
                                    <img src='eckpfeil.gif'>
                                </div>
                                <div class='actionFormLeft'>
                                    <select class='action' name='action'>
                                        <option></option>
                                        <option>Bearbeiten</option>
                                        <option>Entfernen</option>
                                        <option>CSV Export</option>
                                    </select>
                                    <input type='submit' class='actionButton' name='editdelete' value='Go'>
                                </div>
                            </div><!-- END actionForm div -->
                        </form>
                    </div><!-- END hardTableCont div -->
                    <script type='text/javascript'>
                        // MAKING PARENT DIV DEPEND FROM HIS CHILD TABLE  !!! :-)
                        // if table height + 50px (for actionform) is bigger than hard container
                        if (document.getElementById('mytable').offsetHeight >= parseInt(document.getElementById('id_hardTableCont').style.height)-50) {
                            // flexible inner container height is 50px smaller than 
                            document.getElementById('id_flexibleTableCont').style.height = (parseInt(document.getElementById('id_hardTableCont').style.height)-50)+'px'; 
                        } else {
                            document.getElementById('id_flexibleTableCont').style.height = document.getElementById('mytable').offsetHeight;
                        }
                    </script>";
        }
        
        //******************************************************************************************
        
        /*
         
        function renderDeleteTable()
        renders the swowtable with content
        expects the data
        
        */    
        public function renderDeleteTable($data) {
            // render tableheader
            echo   "<div id='id_hardTableCont' class='hardTableCont' style='height:600px'>
                        <form method='GET'>
                            <div id='id_flexibleTableCont' class='flexibleTableCont'>
                                <table id='mytable' class='mytable' cellspacing='1'>
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
            // background of tablerow
            $row_background = "normal_white";            
            // iterate trough data and print on screen
            foreach($data as $actual_dataset) {
                // get arraykeys for checkout actual status
                $keys = array_keys($actual_dataset);
                $key = 0;
                // alternate background of tablerow
                if ($row_background == "normal_white") {
                    $row_background = "normal_grey";
                } else {
                    $row_background = "normal_white";
                }
                echo "<tr>";
                // RENDER THE BIG REST
                foreach ($actual_dataset as $oneentry) {
                    switch ($keys[$key]) {
                        case 'status':
                            // choose background in fact of status
                            switch ($actual_dataset['status']) {
                                case 'FINAL (R)':
                                    echo "<td class='mycell' bgcolor='tomato'>".$oneentry."</td>";
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
                                    echo "<td class='mycell' bgcolor='#FF6'>".$oneentry."</td>";
                                    break;
                                case 'POOL':
                                    echo "<td class='mycell' bgcolor='aqua'>".$oneentry."</td>";
                                    break;
                                case 'TERMIN':
                                    echo "<td class='mycell' bgcolor='greenyellow'>".$oneentry."</td>";
                                    break;
                                default:
                                    echo "<td class='".$row_background."'>".$oneentry."</td>";
                            }
                            break;
                        case 'xing_profile':
                            echo "<td class='".$row_background."'><a href='".$oneentry."'>".$oneentry."</a></td>";
                            break;
                        default:
                            echo "<td class='".$row_background."'>".$oneentry."</td>";
                    }
                    $key++;
                }
                echo    "</tr>";
            }
            echo
                               "</table>
                            </div> <!-- END flexibleTableCont -->
                            <div class='actionForm'>
                                <div class='actionFormRight'>
                                    <input type='submit' class='actionButton' name='confirmDelIDNEU' value='Löschen'>
                                </div>
                            </div>
                        </form>
                    </div> <!-- END hardTableCont -->
                    <script type='text/javascript'>
                        // MAKING PARENT DIV DEPEND FROM HIS CHILD TABLE  !!! :-)
                        // if table height + 50px (for actionform) is bigger than hard container
                        if (document.getElementById('mytable').offsetHeight >= parseInt(document.getElementById('id_hardTableCont').style.height)-50) {
                            // flexible inner container height is 50px smaller than 
                            document.getElementById('id_flexibleTableCont').style.height = (parseInt(document.getElementById('id_hardTableCont').style.height)-50)+'px'; 
                        } else {
                            document.getElementById('id_flexibleTableCont').style.height = document.getElementById('mytable').offsetHeight;
                        }
                    </script>
                </body>
            </html>";
        }
        
        //******************************************************************************************
        
        /*
         
        function renderEditTable()
        expects the data
        
        */    
        public function renderEditTable($data) {
        
            // iterate trough data and print on screen
            foreach($data as $actual_dataset) {
                $keys = array_keys($actual_dataset);
                $key = 0;
                echo "<div class='actionTableCont'><table class='edittable' cellspacing='1'>";
                echo "<form method='GET'>";
                foreach ($actual_dataset as $oneentry) {
                    // switch different input cases generally
                    echo "<tr>";
                    echo "<td  class='editinsertRightAlign'>".self::translate($keys[$key])."</td>";
                    switch ($keys[$key]) {
                        case 'id':
                            echo "<td class='editinsertLeftAlign'><input class='inputDefault' name='".$keys[$key]."' value='".$oneentry."' readonly='readonly'></td>";
                            $key++;
                            break;
                        case 'xing_profile':
                            echo "<td class='editinsertLeftAlign'><input class='xing_input' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                            break;
                        case 'job':
                            echo "<td class='editinsertLeftAlign'>
                            <select class='editInsert 'name='job'>";
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
                            echo "<td class='editinsertLeftAlign'>
                            <select class='editInsert' name='status'>";
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
                            echo "<td class='editinsertLeftAlign'><input class='inputDefault' id='first_contact_at' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                            break;
                        case 'last_update':
                            echo "<td class='editinsertLeftAlign'><input class='inputDefault' id='last_update' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                            break;
                        case 'infos':
                            echo "<td class='editinsertLeftAlign'><textarea class='infos' name='".$keys[$key]."'>".$oneentry."</textarea></td>";
                            break;
                            $key++;
                        default:
                            echo "<td class='editinsertLeftAlign'><input class='inputDefault' name='".$keys[$key]."' value='".$oneentry."'></td>";
                            $key++;
                    } // switch key
                    echo "</tr>";
                } // foreach
                echo "<tr>
                        <td class='editinsertRightAlign'><b>DATENSATZ SPEICHERN<b></td>
                        <td class='editinsertLeftAlign'>
                          <input class='actionButton' type='submit' name='saveEdited' value='Go'></form>
                        </td>  ";
                echo "</table></div>";
            } // foreach
        }
        
        //******************************************************************************************
        
        /*
         
          function renderInsertTable()
          renders the Insert Table        
        
        */
        public function renderInsertTable() {
            echo
            "<div class='actionTableCont'>
            <table class='edittable' cellspacing='1'>
                <form method='GET'>
                    <tr>
                        <td class='editinsertRightAlign'>VORNAME</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' name='firstname'></td>
                    </tr>
                     <tr>
                        <td class='editinsertRightAlign'>NAME</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' name='name'></td>
                     <tr>
                        <td class='editinsertRightAlign'>TÄTIGKEIT</td>
                        <td class='editinsertLeftAlign'>
                            <select class='editInsert' name='job'>
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
                        <td class='editinsertRightAlign'>STATUS</td>
                        <td class='editinsertLeftAlign'>
                            <select class='editInsert' name='status'>
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
                        <td class='editinsertRightAlign'>XING PROFIL</td>
                        <td class='editinsertLeftAlign'><input class='xing_input' id='xing_profile' name='xing_profile'></td>
                    </tr>
                     <tr>
                        <td class='editinsertRightAlign'>ERSTER KONTAKT AM</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' id='first_contact_at' name='first_contact_at'></td>
                    </tr>
                     <tr>
                        <td class='editinsertRightAlign'>ERSTER KONTAKT ÜBER PROFIL</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' name='first_contact_over_profile'></td>
                    </tr>
                     <tr>
                        <td class='editinsertRightAlign'>ERSTER KONTAKT ÜBER MA</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' name='first_contact_from'></td>
                    </tr>
                    <tr>
                        <td class='editinsertRightAlign'>LETZTES UPDATE</td>
                        <td class='editinsertLeftAlign'><input class='inputDefault' id='last_update' name='last_update'></td>
                    </tr>
                    <tr>
                        <td class='editinsertRightAlign'>INFOS</td>
                        <td class='editinsertLeftAlign'><textarea class='infos' name='infos'></textarea></td>
                    </tr>
                    <tr>
                        <td class='editinsertRightAlign'><b>DATENSATZ SPEICHERN</b></td>
                        <td class='editinsertLeftAlign'><input type='submit' class='actionButton' name='saveNew' value='Go'></td>
                    </tr>
                </form>
            </table>
            </div>";
        }
        
        //******************************************************************************************
        
        /*
          
          function renderValidateTable()
          renders the Validate Table
          expects the data
        
        */
        public function renderValidateTable($data) {
            // render tableheader
            echo   "<div id='id_hardTableCont' class='hardTableCont' style='height:600px'>
                        <form method='GET'>
                            <div id='id_flexibleTableCont' class='flexibleTableCont'>
                                <table id='mytable' class='mytable' cellspacing='1'>
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
            // background of tablerow
             $row_background = "normal_white";    
            // iterate trough data and print on screen
            echo "<tr><td class='newData'><font size='1' color='#873549'>X</font></td>
                      <td class='newData'><font color='white'><b>-> Neuer Datensatz</b></font></td>";
            foreach ($data[0] as $oneentry) {
                echo "<td class='newData'><font color='white'><b>".$oneentry."</b></font></td>";
            }
                                   
            foreach($data[1] as $actual_dataset) {
                // get arraykeys for checkout actual status
                $keys = array_keys($actual_dataset);
                $key = 0;
                // alternate background of tablerow
                if ($row_background == "normal_white") {
                    $row_background = "normal_grey";
                } else {
                    $row_background = "normal_white";
                }
                echo "<tr>";
                 echo "<td class='default'>
                        <input type='checkbox' name='choosed[]' value='".$actual_dataset['id']."'>
                       </td>";
                // RENDER THE BIG REST
                foreach ($actual_dataset as $oneentry) {
                    // choose background in fact of status
                    switch ($keys[$key]) {
                        case 'status':
                            // choose background in fact of status
                            switch ($actual_dataset['status']) {
                                case 'FINAL (R)':
                                    echo "<td class='mycell' bgcolor='tomato'>".$oneentry."</td>";
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
                                    echo "<td class='mycell' bgcolor='FF6'>".$oneentry."</td>";
                                    break;
                                case 'POOL':
                                    echo "<td class='mycell' bgcolor='aqua'>".$oneentry."</td>";
                                    break;
                                case 'TERMIN':
                                    echo "<td class='mycell' bgcolor='greenyellow'>".$oneentry."</td>";
                                    break;
                                default:
                                    echo "<td class='".$row_background."'>".$oneentry."</td>";
                            }
                            break;
                        default:
                            echo "<td class='".$row_background."'>".$oneentry."</td>";
                    }
                    $key++;
                }
                echo    "</tr>";
            }
            echo
                               "</table>
                            </div> <!-- END div flexibleTableCont -->
                            <div class='actionForm'>
                                <div class='arrow'>
                                    <img src='eckpfeil.gif'>
                                </div>
                                <div class='actionFormLeft'>
                                    <input type='submit' class='actionButton' name='editOld' value='Alten Datensatz Bearbeiten'>
                                </div>
                                <div class='actionFormRight'>
                                    <input type='submit' class='actionButton' name='confirmInsert' value='Neuen Datensatz Speichern'>
                                </div>
                            </div> <!-- END actionForm div -->
                        </form> 
                    </div> <!-- END div hardTableCont -->
                    <script type='text/javascript'>
                        // MAKING PARENT DIV DEPEND FROM HIS CHILD TABLE  !!! :-)
                        // if table height + 50px (for actionform) is bigger than hard container
                        if (document.getElementById('mytable').offsetHeight >= parseInt(document.getElementById('id_hardTableCont').style.height)-50) {
                            // flexible inner container height is 50px smaller than 
                            document.getElementById('id_flexibleTableCont').style.height = (parseInt(document.getElementById('id_hardTableCont').style.height)-50)+'px'; 
                        } else {
                            document.getElementById('id_flexibleTableCont').style.height = document.getElementById('mytable').offsetHeight;
                        }
                    </script>
                </body>
            </html>"; 
        }
        
        //******************************************************************************************
        
        /*
          
          function renderMainMenu()
          renders the main menu
          
        */
        public function renderMainMenu() {
            echo
                       "<div class='menu'><!--BEGIN div class 'menu'-->
                        <div class='menu1'>
                            <form method='GET'>
                            <input class='menu' type='submit' name='insert' value='Datensatz erstellen'>
                            <input class='menu' type='submit' name='showall' value='Alle Datensätze anzeigen'>
                            </form>
                        </div>
                        <div class='menu2'>
                        <form method='GET'>
                            <font size='2'>Name: </font><input class='searchterm_name' name='searchterm_name'>
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
                            <input class='searchbutton' type='submit' name='search' value='Suchen'><br>
                        </form>
                        </div>
                        </div><!--END div class 'menu'-->";
        }
        
        //******************************************************************************************
        
        /*
          
          function renderBackButton()
          renders the main menu
          
        */        
        public function renderBackButton() {
            echo "  <div class='menu'><!--BEGIN div class 'menu'-->
                        <div class='menu1'>
                            <form method='GET'>
                                <input class='menu' type='submit' name='back' value='Zurück zum Hauptmenü'>
                            </form>
                        </div>
                    </div><!--END div class 'menu'-->";
        }
        
         //*****************************************************************************************
        
        /*
          
          function renderTableHeader()
          renders ONLY the table header
        
        */
        public function renderTableHeader() {
            echo   "<div id='id_hardTableCont' class='hardTableCont' style='height:600px'>
                        <div id='id_flexibleTableCont' class='flexibleTableCont'>
                            <table class='mytable' cellspacing='1'>
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
                                <th class='default'>INFOS</th>
                            </table>
                        </div>
                    </div>
                    <script type='text/javascript'>
                        // MAKING PARENT DIV DEPEND FROM HIS CHILD TABLE  !!! :-)
                        // if table height + 50px (for actionform) is bigger than hard container
                        if (document.getElementById('mytable').offsetHeight >= parseInt(document.getElementById('id_hardTableCont').style.height)-50) {
                            // flexible inner container height is 50px smaller than 
                            document.getElementById('id_flexibleTableCont').style.height = (parseInt(document.getElementById('id_hardTableCont').style.height)-50)+'px'; 
                        } else {
                            document.getElementById('id_flexibleTableCont').style.height = document.getElementById('mytable').offsetHeight;
                        }
                    </script>";  
        }
        
        //******************************************************************************************
        
        /*
          
          function translate()
          translate some special english terms into german
          expects the english term
        
        */
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