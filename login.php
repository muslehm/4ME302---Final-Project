<?php
session_start();
$_Session['service']= 'google';
require_once '../init.php';
$authUrl = $auth->checkToken();

if($auth->login())
{
$redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php'; 
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

?>