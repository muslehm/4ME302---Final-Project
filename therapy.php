<?php
   
    $userId;
    $medId;
    $medUsername;
    $patUsername;
    $therapy;
    $dosage;
    $medicine;
    $db = new myDB;
      echo '<script type = "text/javascript" src = "https://d3js.org/d3.v4.min.js"></script>';

        $psql="SELECT userID FROM User WHERE username = '{$_SESSION['username']}'";
        $userId = $db->query($psql)->fetch_object()->userID;
        if($_SESSION['myrole']==1)
        {
            //For testing purposes, we commented out a dynamic fetching of data based on user, and set a testing userID
            //$psql="SELECT User_IDmed, TherapyList_IDtherapylist FROM Therapy WHERE User_IDpatient = {$userId}";
            $psql="SELECT User_IDmed, TherapyList_IDtherapylist, therapyID FROM Therapy WHERE User_IDpatient = 3";
            $medId_ = $db->query($psql);

            if($medId_ && $medId_->num_rows>0)
            {   
               $row = $medId_->fetch_all(MYSQLI_ASSOC);
               $psql="SELECT * FROM Test WHERE Therapy_IDtherapy={$row[0]['therapyID']}";
                $patTest = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
                $newarray = array();
                $newarrayD = array();
                $newarrayT = array();
               for($i=0; $i<sizeof($patTest);$i++)
               {
                $psql="SELECT test_type FROM Test_Session WHERE Test_IDtest={$patTest[$i]['testID']}";
                $typeTest = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
                
                array_push($newarrayD,$patTest[$i]['dateTime'] );
                array_push($newarrayT,sizeof($typeTest) );
                }
              
                array_push($newarray, $newarrayD, $newarrayT);
                echo '<div style="height:330px;"><div class="patchart" style="width: 300px; float:left; height:200px; margin:10px">Here are the stats of your exercises:                                       <br/></div>';
                echo '<script type="text/javascript">graphbar('.json_encode($newarray).');</script>';
                $psql="SELECT username FROM User WHERE userID = {$row[0]['User_IDmed']}";
                $medUsername = $db->query($psql)->fetch_object()->username; 
                for ($x = 0; $x < sizeof($row); $x++) {

                $psql="SELECT name, Dosage, Medicine_IDmedicine FROM Therapy_List WHERE therapy_listID = {$row[$x]      
                ['TherapyList_IDtherapylist']}";
                $therapylist = $db->query($psql);
                $row2 = $therapylist->fetch_array(MYSQLI_ASSOC);
                $therapy = $row2['name'];
                $dosage = $row2['Dosage'];

                $psql="SELECT name FROM Medicine WHERE medicineID = {$row2['Medicine_IDmedicine']}";
                $medicine =$db->query($psql)->fetch_object()->name; 

        
                $html = '<div style="width: 500px; float:left; height:300px; margin:10px"><h4 align="left">Dr. '.$medUsername.'                                  recommends '.$therapy.'* for you.</h4>
                            <p align="left">*&nbsp;'.$medicine.' with '.$dosage.' dosage</p></div>';
                echo $html;
                }
            }
            else
            {
            echo "<h4 align='left'>You have no therapy recommendations. </h4>";
            }
        }
        elseif($_SESSION['myrole']==2)
        {
            //$psql="SELECT User_IDpatient, TherapyList_IDtherapylist FROM Therapy WHERE User_IDmed = {$userId}";
            $psql="SELECT User_IDpatient, TherapyList_IDtherapylist FROM Therapy WHERE User_IDmed = 1";
            $medId_ = $db->query($psql);
            
            if($medId_ && $medId_->num_rows>0)
            {   
                
               $row = $medId_->fetch_all(MYSQLI_ASSOC);
               
               
              
                for ($x = 0; $x < sizeof($row); $x++) {
                $psql="SELECT username FROM User WHERE userID = {$row[$x]['User_IDpatient']}";
                $patUsername = $db->query($psql)->fetch_object()->username; 

                $psql="SELECT name, Dosage, Medicine_IDmedicine FROM Therapy_List WHERE therapy_listID = {$row[$x]      
                ['TherapyList_IDtherapylist']}";
                $therapylist = $db->query($psql);
                $row2 = $therapylist->fetch_array(MYSQLI_ASSOC);
                $therapy = $row2['name'];
                $dosage = $row2['Dosage'];

                $psql="SELECT name FROM Medicine WHERE medicineID = {$row2['Medicine_IDmedicine']}";
                $medicine =$db->query($psql)->fetch_object()->name; 

        
                $html = "<h4 align='left'>Your recommended for patient ".$patUsername." the following therapy:</h4>
                <p align='left'>".$therapy." [".$medicine." with ".$dosage." dosage]</p>";
            
                echo $html;}
            }
            else
            {echo "<h4 align='left'>You have no Patients. </h4>";}
        }
        else
        {
            echo "ALERT: YOU ARE NOT ALLOWED HERE!";
        }
        
