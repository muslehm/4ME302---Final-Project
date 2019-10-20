<?php

session_start();
require_once 'config.php';
require_once 'clients.php';
$db = new myDB;
if(isset($_Session['username']))
{
}
else
{
    if(isset($_Session['service']) && !empty($_Session['service']))
    {   
        
        if($_Session['service']=='google')
        {//Config Google
        
        require_once 'vendor/autoload.php';
        
        require_once 'auth/googleAuth.php';
        $gClient = new Google_Client;
        $auth = new GoogleAuth($db, $gClient);
        $authUrl = $auth->checkToken();
         }

        elseif($_Session['service']=='github')
        {//Config Github
        require_once 'auth/src/Github_OAuth_Client.php';
        require_once 'auth/githubAuth.php';
        $gitClient = new Github_OAuth_Client($gitClientArray);
        $authGit = new GitAuth($db, $gitClient); 
        $authUrl = $authGit->checkToken();
        }
    
        elseif($_Session['service']=='linkedin')
        {//Config Linkedin
        require_once 'auth/src/Linkedin_OAuth_Client.php';
        require_once 'auth/linkedinAuth.php';
        $liClient = new Linkedin_OAuth_Client($liClientArray);
        $authLi = new LiAuth($db, $liClient); 
        $authUrl = $authLi->checkToken();}
    
        else
        {
        unset($_Session['service']);
        $redirect = 'http://'.$_SERVER['SERVER_NAME'].'/index.php'; 
        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }
        
        header('Location: ' .$authUrl );
    }
    
}


?>