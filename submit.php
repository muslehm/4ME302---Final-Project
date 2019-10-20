<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'init.php';
if (isset($_REQUEST['coordinates'])) {

  
    $db = new myDB;
    $mynote = htmlspecialchars($_POST["coordinates"]).'z'.htmlspecialchars($_POST["annotation"]);
   

   // User_IDmed is userID from User where username equals $_SESSION['username'] but commenting it for testing purposes
   //$psql="SELECT userID FROM User WHERE username = '{$_SESSION['username']}'";
   //$userid = $db->query($psql)->fetch_object()->userID; 
    if($_SESSION['myrole']==2)
        $userid = 1;
    elseif($_SESSION['myrole']==3)
        $userid= 2;

    
    //Test_Session_IDtest_session is test_SessionID where DataURL is $dataurl;
   $dataurl= htmlspecialchars($_POST["dataset"]);
   $psql="SELECT test_SessionID FROM Test_Session WHERE DataURL = '$dataurl'";
   $testsessionid = $db->query($psql)->fetch_object()->test_SessionID; 
   
 
  

   $asql="
         INSERT INTO Note (Test_Session_IDtest_session, note, User_IDmed)
          VALUES ({$testsessionid}, '{$mynote}', {$userid})
         ";
         
         $db->query($asql);
        $redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php?p=data'; 
  header('Location: ' . $redirect);

} else {
    die('User did not send any data to be saved!');
}



?>