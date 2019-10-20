<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_GET['l']) && !empty($_GET['l']))
{  
    $_Session['service']=$_GET['l'];
    require_once 'init.php';
    
   
}
else 
{
    header('Location: index.php');
}
?>

