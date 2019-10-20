<?php
$db = new myDB;
    //loading libraries of API
    echo '<script type = "text/javascript" src = "https://d3js.org/d3.v4.min.js"></script>
    <script src="https://rawgit.com/susielu/d3-annotation/master/d3-annotation.min.js"></script>';

if($_SESSION['myrole']==2)
{
    $userIDmed = 1;
}
elseif($_SESSION['myrole']==3)
{
    $userIDmed = 2;
    echo '<h2>Patients Locations</h2><div id="map" class="map"></div></div>';
    echo '<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/build/ol.js"></script> <script         
    type="text/javascript">loadmap();</script>';

    //fetch distinct patients who had tests
    $psql="SELECT  DISTINCT a.User_IDpatient FROM Therapy a INNER JOIN Test b ON a.therapyID = b.Therapy_IDtherapy";
    $patientsId_ = $db->query($psql);
    $patientsId = $patientsId_->fetch_all(MYSQLI_ASSOC);
                
    //fetch patients locations
    for($i=0;$i<sizeof($patientsId); $i++)
    {
        $psql="SELECT username, Lat, Lon FROM User WHERE userID = {$patientsId[$i]['User_IDpatient']}";
        $patLatLong = $db->query($psql)->fetch_array(MYSQLI_ASSOC); 
        //set vectors for patient locations
        echo '<script type="text/javascript">loadlocations('.$patLatLong['Lat'].','.$patLatLong['Lon'].');</script>';
        
        }
    
    }

else
{
    return '';
}
    
    if($_SESSION['myrole']==3)
    {   

        if(empty($_SESSION['showdata']))
            {$row = [];}
            else
            {
                
                
                $psql="SELECT therapyID FROM Therapy WHERE User_IDpatient = {$_SESSION['showdata']}";
                $therId = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
        
                $psql="SELECT testID FROM Test WHERE Therapy_IDtherapy = {$therId[0]['therapyID']}";
                $theTests = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
                $row=[];

                for ($x = 0; $x < sizeof($theTests); $x++) {
                    $psql="SELECT DataURL, test_SessionID, Test_IDtest FROM Test_Session WHERE Test_IDtest= {$theTests[$x]['testID']}";
                    $dataurl_ = $db->query($psql);
                    $newarray = $dataurl_->fetch_all(MYSQLI_ASSOC);
                    for ($y = 0; $y < sizeof($newarray); $y++){
                        array_push($row, $newarray[$y]);}
    
                        
                
                     }

                }

        
    }

   
    elseif($_SESSION['myrole']==2)
    {
    $psql="SELECT DataURL, test_SessionID, Test_IDtest FROM Test_Session";
    $dataurl_ = $db->query($psql);
    $row = $dataurl_->fetch_all(MYSQLI_ASSOC);
    
    }

    else
    {return '';}
    
    for ($x = 0; $x < sizeof($row); $x++) {
     
        $psql="SELECT dateTime, Therapy_IDtherapy FROM Test WHERE testID = '{$row[$x]['Test_IDtest']}'";
        $theTest = $db->query($psql)->fetch_all(MYSQLI_ASSOC);

        $psql="SELECT User_IDpatient, TherapyList_IDtherapylist FROM Therapy WHERE therapyID = {$theTest[0]['Therapy_IDtherapy']}";
        $medId = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
        //To only show data connected to medical personel therapy, User_IDmed wiill be selected from above to use for authorization  
        
        $psql="SELECT name, Dosage FROM Therapy_List WHERE therapy_listID = {$medId[0]['TherapyList_IDtherapylist']}";
        $therapylist = $db->query($psql)->fetch_array(MYSQLI_ASSOC);
        
        $psql="SELECT username FROM User WHERE userID = {$medId[0]['User_IDpatient']}";
        $patUsername = $db->query($psql)->fetch_object()->username;

        
        
        $psql="SELECT note FROM Note WHERE (Test_Session_IDtest_session = {$row[$x]['test_SessionID']} AND User_IDmed = {$userIDmed})";
        if($db->query($psql)->fetch_all(MYSQLI_ASSOC)){
        $notes = $db->query($psql)->fetch_all(MYSQLI_ASSOC);}
        else{$notes="";}
        
        echo '<button type="button" width="450px" class="collapsible" onClick="collapse()">'.$row[$x]['DataURL'].' for '.$patUsername.'. Test on                    '.$theTest[0]['dateTime'].'</button>
        <div class="content" style="position: static;">
        <p><b>Therapy:</b> '.$therapylist['name'].' with dosage of '.$therapylist['Dosage'].'</p>
   
        <div id="'.$row[$x]['DataURL'].'" style="float: left;" ></div>
   
        <div id="'.$row[$x]['DataURL'].'L" onclick="showCoords(event,'.$row[$x]['DataURL'].' )" style="float: right;" ></div>
    
        <div class="datanotes" style="clear: both;">
            <form method="post" action="submit.php">Click on Line Graph to select coordinates. Add annotation of 50 characters max<br/>
                <input type="text" id="'.$row[$x]['DataURL'].'n" name="coordinates" value="x60y20" readonly><br/>
               <input type="text" id="'.$row[$x]['DataURL'].'an" name="annotation" value="" maxlength="50" style="width: 300px;"></br>
               <input type="hidden" name="dataset" value="'.$row[$x]['DataURL'].'">
              <input type="submit" name="savetodb" value="Annotate">
          </form>
          <br/>
        </div>
        </div>';
         //read csv files
        $stringtest = $row[$x]['DataURL'];
        $psql="SELECT test_type FROM Test_Session WHERE DataURL = '{$row[$x]['DataURL']}'";
        $type = $db->query($psql)->fetch_object()->test_type;
        $mydatafile = 'data/'.$stringtest.'.csv';
        ini_set('auto_detect_line_endings',TRUE);
        $file = fopen($mydatafile,"r");
        $mydata = Array();
        $xaxis = Array();
        $yaxis = Array();
        $time = Array();
        $button = Array();
        $correct = Array();
        // extraxt coordinates into separate arrays
        $header = fgetcsv($file);
        while(! feof($file))
        {
        $mydata = fgetcsv($file);
        array_push($xaxis, $mydata[0]);
        array_push($yaxis, $mydata[1]);
        array_push($time, $mydata[2]);
        if($type==2)
            {
                if($mydata[4]==1)
                    array_push($button, "blue");
                elseif($mydata[4]==0 && $mydata[3]==0)
                    array_push($button, "red");
                else
                    array_push($button, "orange");
                
                  

                

            }
        }
        $result = array($xaxis, $yaxis);
        fclose($file);
        ini_set('auto_detect_line_endings',FALSE);
        //get Type of data
    

        echo '<script type="text/javascript">init( '.json_encode($yaxis).','.json_encode($xaxis).','.json_encode($time).','.json_encode($button).',                 "'.$stringtest.'",'.$type.','.json_encode($notes).');</script>';
       
        
    }
   



?>
