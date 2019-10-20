<?php
class LiAuth
{ 
    private $db;
    private $client;

//Construct the Github class
    public function __construct(myDB $db, Linkedin_OAuth_Client $liClient)
    {   
     $this->db = $db;
     $this->client = $liClient;
    }
  public function checkToken()
    { 
        
         if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token']))
        {
            
           
        }
        else
        {
          //CHANGE
            $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
        
           return $this->client->getDialogURL($_SESSION['state']);
           
        }
        
        return'';
       
        
    }
   public function login()
    {
        //CHANGE
        
        if(isset($_GET['code']))
        {
          
            $_SESSION['access_token'] = $this->client->getAccessToken($_GET['code']);
            $access_token = $_SESSION['access_token'];
         /* Get User Info */
        $method = 0; // method = 0, because we want GET method
        $url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName)"; 
        $header = array("Authorization: Bearer $access_token");
        $data = 0; // data = 0, because we do not have data
        $json = 1; // json = 1, because we want JSON response
        $user_info = $this->client->HTTP_Request($method, $url, $header, $data, $json);
        echo '<pre>', print_r($user_info), '</pre>';
        $firstname = $user_info['firstName']['localized']['en_US'];
        $lastname =  $user_info['lastName']['localized']['en_US'];
        $_SESSION['username']= $firstname.$lastname.'linkedin';
        $_SESSION['myrole']= 2;
        $_SESSION['user_id']=$user_info['id'];
        $this->storeUser();

        return true;
        }
            
        return false;

    }
  
    
    public function storeUser() 
    {    
    
         
         $asql="
         INSERT INTO User (username, email, Role_IDrole, organization, clientID)
         VALUES ('{$_SESSION['username']}', ' ', 2, 1, '{$_SESSION['user_id']}')
         ON DUPLICATE KEY UPDATE userid=userid
         ";
         $this->db->query($asql);
         

    }
    
   
}
    
