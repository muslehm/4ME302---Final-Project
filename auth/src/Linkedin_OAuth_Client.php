<?php
/*
 * oauth_client.php
 *
 * @(#) $Id: oauth_client.php,v 1.108 2014/06/12 10:58:53 mlemos Exp $
 *
 */



class Linkedin_OAuth_Client
{
    
        
		
		
		public $oauth_version = '10a';
		public $request_token_url = 'https://api.linkedin.com/oauth/v2/requestToken?scope={SCOPE}';
		public $dialog_url = 'https://www.linkedin.com/oauth/v2/authorization';
		public $access_token_url = 'https://api.linkedin.com/oauth/v2/accessToken';
        public $apiURLBase = 'https://api.linkedin.com/';
		public $url_parameters = true;
        public $redirect_uri = '';
	    public $client_id = '';
	    public $client_secret = '';
        public $offline_dialog_url = '';
        public $offline = false;
        public $scope = '';
        public $api_key = '';
        public $state='';
        public $debug = 1;
	    public $debug_http = 1;



    public function __construct(array $config = []){
        $this->client_id = isset($config['client_id']) ? $config['client_id'] : '';
        if(!$this->client_id){
            die('Required "client_id" key not supplied in config');
        }
        
        $this->client_secret = isset($config['client_secret']) ? $config['client_secret'] : '';
        if(!$this->client_secret){
            die('Required "client_secret" key not supplied in config');
        }
        $this->scope = isset($config['scope']) ? $config['scope'] : '';
        if(!$this->scope){
            die('Required "scope" key not supplied in config');
        }
        
        $this->redirect_uri = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
    }



	public function getDialogURL($stateS)
	{
		$url = (($this->offline && strlen($this->offline_dialog_url)) ? $this->offline_dialog_url : $this->dialog_url);
        $this->state=$stateS;
		if(strlen($url) === 0)
        {

			return $this->SetError('the dialog URL '.($this->offline ? 'for offline access ' : '').'is not defined for this server');
        }
        
        else
        {

         
              return $url . '?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'state' => $this->state,
            'scope' => 'r_liteprofile'
        ]);
		}
	}
    


    public function getAccessToken($oauth_code)
    {
        echo "hi";
        echo $oauth_code;
       $url_ = $this->access_token_url;
       echo $url_;
       $method_ = 1;
        $header_ = array( "Content-Type: application/x-www-form-urlencoded" );
        $data_ = http_build_query([
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://musleh.epizy.com/linkedin/login.php',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $oauth_code
        ]);
        $json_ = 1;
        $get_access_token = $this->HTTP_Request($method_, $url_, $header_, $data_, $json_);
        $access_token = $get_access_token['access_token'];
        return $access_token;
        
    }

public function HTTP_Request($method, $url, $header, $data, $json){
    if( $method == 1 ){
    $method_type = 1; // 1 = POST
        }else{
        $method_type = 0; // 0 = GET
        }
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
 
        if( $header !== 0 ){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
 
        curl_setopt($curl, CURLOPT_POST, $method_type);
 
        if( $data !== 0 ){
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
 
        $response = curl_exec($curl);
 
        if( $json == 0 ){
        $json = $response;
        }else{
        $json = json_decode($response, true);
        }
 
        curl_close($curl);
 
        return $json;
    }
    
	
	
}
?>
