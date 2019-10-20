<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'init.php';
if (isset($_GET['latlonid'])) {

    $db = new myDB;
    $latlon = htmlspecialchars($_GET["latlonid"]);
    
    $findpos   = '_';
    $pos = strpos($latlon, $findpos);
    $latn = substr($latlon, 0, $pos);
    $lonn = substr($latlon, $pos+1);
    
    $psql="SELECT userID FROM User WHERE Lat = $latn AND Lon = $lonn ";
    $theuserID = $db->query($psql)->fetch_object()->userID; 
       
        
        $_SESSION['showdata'] = $theuserID;
       
    


   
        $redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php?p=data'; 
        header('Location: ' . $redirect);


}
else {
    die('User did not send any data to be saved!');
}
