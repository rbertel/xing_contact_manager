<?php

    // NUR EINE TESTDATEI
    
    include_once("Model_Home.php");
     echo "HALO";
    $model = new Model_Home();
   
    
    foreach($model->datasets as $actual_dataset) {
        foreach ($actual_dataset as $oneentry) {
            echo $oneentry . " , ";
        }
        echo "<br>";
    }
    
    echo "<br>";
    echo "<br>";
   
    foreach($model->searchDataset("saf","name") as $actual_dataset) {
        foreach ($actual_dataset as $oneentry) {
            echo $oneentry . " , ";
        }
        echo "<br>";
    }
    
    $model->insertDataset(NULL, "abcd","ab23232c","abc","a222bc","abc","abc","abc","abc","abc","abc");
    
     $model->updateDataset("3", "abc","abc","abc","abc","abc","abc","abc","abc","abc","abc");
    

    
    