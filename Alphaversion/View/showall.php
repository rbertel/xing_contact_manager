<?php  
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
        
$this->view_home->renderMenu();
// render tableheader
echo   "<div class='actionTableCont'>
            <form method='POST'>
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
                    <th class='default'>INFOS</th>";
// background of tablerow
$row_background = "normal_white"; 
// iterate trough data and print on screen
foreach($this->model_home->getDatasets() as $actual_dataset) {
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
                        echo "<td class='mycell' bgcolor='yellow'>".$oneentry."</td>";
                        break;
                    case 'POOL':
                        echo "<td class='mycell' bgcolor='cyan'>".$oneentry."</td>";
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
echo    "<tr>";
echo   "<td class='lastrow'>
            <input id='checkall' type='checkbox' name='chooseAll' onclick='checkedall()'>
        </td>";
// alternate background of tablerow for last row 
if ($row_background == "normal_white") {
    $row_background = "normal_grey";
} else {
    $row_background = "normal_white";
}
echo        "<td class='lastrow' colspan='12'>Alle auswählen</td>";
echo
    "</table>
     <div class='arrow'><img src='eckpfeil.gif'></div>
     <div class='actionFormLeft'>
         <select class='action' name='action'>
             <option></option>
             <option>Bearbeiten</option>
             <option>Entfernen</option>
             <option>CSV Export</option>
         </select>
         <input type='submit' class='actionButton' name='editdelete' value='Go'>
     </div></form></div>";
?>
</body>
</html>
