 <?php

$apikey = 'AIzaSyBKQzeZeULXfAW79RGhpFYiw6q7irSwJjU';
 
$arr_list = array();
$db = new myDB;


/*
//commented out for testing purposes
$psql="SELECT userID FROM User WHERE username = '{$_SESSION['username']}'";
$userId = $db->query($psql)->fetch_object()->userID;
*/
//For testing purposes, we commented out a dynamic fetching of data based on user, and set a testing userID
            //$psql="SELECT User_IDmed, TherapyList_IDtherapylist FROM Therapy WHERE User_IDpatient = {$userId}";
            $psql="SELECT User_IDmed, TherapyList_IDtherapylist, therapyID FROM Therapy WHERE User_IDpatient = 3";
            $medId_ = $db->query($psql);
            $userID=3;

 if($medId_ && $medId_->num_rows>0)
            {   
            $row = $medId_->fetch_all(MYSQLI_ASSOC);
            $psql="SELECT * FROM Test WHERE Therapy_IDtherapy={$row[0]['therapyID']}";
            $patTest = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
            $testsessions = array();
               for($i=0; $i<sizeof($patTest);$i++)
               {
                   
                $psql="SELECT test_SessionID FROM Test_Session WHERE Test_IDtest={$patTest[$i]['testID']}";
                $testsessionId = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
                for($z=0; $z<sizeof($testsessionId);$z++)
                     {
                     array_push($testsessions,$testsessionId[$z]);
                    }
               }
            }
            $theusernotes = array();
            $Balance=0;
            $Stamina=0;
          
            for($i=0; $i<sizeof($testsessions);$i++)
               { 
                   
                   $psql="SELECT note FROM Note WHERE Test_Session_IDtest_session={$testsessions[$i]['test_SessionID']} AND User_IDmed={$row[0]['User_IDmed']}";
                $thenotes = $db->query($psql)->fetch_all(MYSQLI_ASSOC);
                 
                 for($z=0; $z<sizeof($thenotes);$z++)
                     {
                         
                 
               
                    $zpos = strpos($thenotes[$z]['note'], 'z');
                     //array_push($theusernotes,$thenotes[$z][$zpos+1]);
                    $eachnote=substr($thenotes[$z]['note'], $zpos+1);
                    $keywordlist = explode(" ", $eachnote);
                     for($x=0; $x<sizeof($keywordlist); $x++)
                     {
                        if(strtolower($keywordlist[$x])=='balance' || substr(strtolower($keywordlist[$x]), 0, -1)=='balance')
                        {
                            $Balance++;
                        }
                         if(strtolower($keywordlist[$x])=='stamina' || substr(strtolower($keywordlist[$x]), 0, -1)=='stamina')
                        {
                            $Stamina++;
                        }
                     }
                    }
                    
               }
              
              // echo sizeof($theusernotes);
              // print_r($usernotes);
//$arraykeywords = explode(" ", $arratkeyword);



$keyword = "Parkinson+Exercises";
if($Balance>0)
$keyword = $keyword.'+balance';
if($Stamina>0)
$keyword = $keyword.'+stamina';
$url = "https://www.googleapis.com/youtube/v3/search?q=".$keyword."&part=snippet&type=playlist&maxResults=10&key=". $apikey;
 
$arr_list = fetchList($url);
 
if (!empty($arr_list)) {
    
    foreach ($arr_list->items as $yt) {
      

     echo '<button type="button" width="1000px" class="collapsible" onClick="collapse()">'.$yt->snippet->title.'</button>
        <div class="content" style="position: static; width:1000px; height:529px;">
     <iframe width="1000" height="529"  src="https://www.youtube.com/embed/playlist?list='.$yt->id->playlistId.'" allowfullscreen></iframe> ;
       
        </div>';
 
    }


    }
else
    {echo "No Results to display!";}
  
  
function fetchList($fetchurl) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fetchurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $exerciseslist = json_decode($response);
    if (isset($exerciseslist->items)) {
       
       return $exerciseslist;
      
    } elseif (isset($arr_result->error)) {
       return "No Results!";
    }
}
?>

