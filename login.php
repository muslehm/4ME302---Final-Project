<?php
session_start();
$_Session['service']= 'linkedin';
require_once '../init.php';

$authUrlL = $authLi->checkToken();

if($authLi->login())
{
$redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php'; 
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

?>