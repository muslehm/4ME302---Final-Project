<?php
class GoogleAuth
{
   
    private $db;
    private $client;

    public function __construct(myDB $db, Google_Client $gClient)
    {
        
     $this->db = $db;
     $this->client = $gClient;

     $this->client->setClientID('');
     $this->client->setClientSecret('');
     $this->client->setRedirectUri('http://musleh.epizy.com/google/login.php');
     $this->client->setScopes('email');
 
    
     
    }

    //If an access token is available in the session, set it, if not return an Authentication URL
    public function checkToken()
    {
       
        if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token']))
        {
            $this->client->setAccessToken($_SESSION['access_token']);
        }
        else
        {
        return $this->client->createAuthUrl();
        }

        return'';
    }
    public function login()
    {
        if(isset($_GET['code']))
        {
        
            $this->client->authenticate($_GET['code']);
             
            $_SESSION['access_token'] = $this->client->getAccessToken();
            $_SESSION['myrole']= 1;
            $this->storeUser($this->getPayload());
            return true;
            
        }
        return false;

    }
   
    public function getPayload()
    {
         return json_decode(json_encode($this->client->verifyIdToken()));  
    }  
    public function storeUser($payload) 
    { 
         $arr = explode("@", $payload->email, 2); 
         $_SESSION['username']= $arr[0];
         $asql="
         INSERT INTO User (username, email, Role_IDrole, organization, clientID)
         VALUES ('{$_SESSION['username']}', '{$payload->email}', 1, 1, '{$payload->sub}')
         ON DUPLICATE KEY UPDATE userid=userid
         ";
         
         $this->db->query($asql);

    }

}
    
