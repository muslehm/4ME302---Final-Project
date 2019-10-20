<?php
session_start();
$_Session['service']= 'github';
require_once '../init.php';

$authUrlG = $authGit->checkToken();

if($authGit->login())
{
   
$redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php'; 
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

?>
