<?php
class GitAuth
{ 
    private $db;
    private $client;

//Construct the Github class
    public function __construct(myDB $db, Github_OAuth_Client $gitClient)
    {   
     $this->db = $db;
     $this->client = $gitClient;
    }
  public function checkToken()
    { 
        
         if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token']))
        {
            //$gitUser = $gitClient->apiRequest($accessToken);
           
        }
        else
        {
          
            $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
            
           return $this->client->getAuthorizeURL($_SESSION['state']);
        }
        
        return'';
       
        
    }
     public function login()
    {
        if(isset($_GET['code']))
        {
            $_SESSION['myrole']= 3;
            $_SESSION['access_token'] = $this->client->getAccessToken($_GET['state'], $_GET['code']);
            $accessToken = $_SESSION['access_token'];
            $gitUser = $this->client->apiRequest($accessToken);
            $gitUserData = array();
            $gitUserData['oauth_provider'] = 'github';
            $gitUserData['oauth_uid'] = !empty($gitUser->id)?$gitUser->id:'';
            $gitUserData['username'] = !empty($gitUser->login)?$gitUser->login:'';
            $gitUserData['email'] = !empty($gitUser->email)?$gitUser->email:'';
            $this->storeUser($gitUserData);
            return true;
            
        }
        return false;

    }
    public function storeUser($userData) 
    {    
         $_SESSION['username']= $userData['username']."_".$userData['oauth_provider'];
         $asql="
         INSERT INTO User (username, email, Role_IDrole, organization, clientID)
         VALUES ('{$_SESSION['username']}', '{$userData['email']}', 3, 2, '{$userData['oauth_uid']}')
         ON DUPLICATE KEY UPDATE userid=userid
         ";
         
         $this->db->query($asql);
         

    }
    
   
}
    