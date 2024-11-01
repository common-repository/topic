<?php 
if( ! defined( 'ABSPATH') ) {
    exit;
}

/**
 * UseTopic API's
 */

class UseTopicAPI {

    // url to usetopic api
    protected $apiUrl = 'https://app.usetopic.com/api/';
    protected $username;
	protected $password;
	protected $token;
	protected $briefID;
	protected $briefcontent;
	protected $searchkey;

    public function authenticateUsetopicUser( $username, $password ){

       $this->username = $username;
       $this->password = $password;

       $url = $this->apiUrl . 'login?email='.urlencode( $this->username ).'&password='.urlencode( $this->password );
       return $response = $this->useTopicAuthCurl( $url );


    }

    public function getBriefsbyTopicToken( $token ){

       $this->token = $token;
       return $response = $this->useTopicBriefsCurl( $this->token );

    }

   public function getBriefDetailByID( $briefid, $token ){
   	 	$this->token = $token;
       	$this->briefID = $briefid;
        return $response = $this->useTopicBriefDetailCurl( $this->briefID, $this->token );

    }
    public function getBriefDetailByURL( $briefid ){
       	$this->briefID = $briefid;
        return $response = $this->useTopicBriefDetailURLCurl( $this->briefID );

    }

    public function getBriefUpdatedReport( $briefid, $token, $content ){
   	 	$this->token = $token;
       	$this->briefID = $briefid;
       	$this->briefcontent = $content;
        return $response = $this->getBriefUpdatedReportCurl( $this->briefID, $this->token, $this->briefcontent );

    }
    public function getBriefUpdatedReportURL( $briefid, $content ){

       	$this->briefID = $briefid;
       	$this->briefcontent = $content;
        return $response = $this->getBriefUpdatedReportURLCurl( $this->briefID, $this->briefcontent );

    }
    public function getBriefsBySearch( $search, $briefToken ){

       	$this->searchkey 	= $search;
       	$this->token 		= $briefToken;
        return $response = $this->getBriefsBySearchCurl( $this->searchkey, $this->token );

    }
    private function useTopicAuthCurl( $url ){
    	$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec( $curl );

		curl_close( $curl );
		return $response;
    }

	private function useTopicBriefsCurl( $token ){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl.'keyword_reports/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$token
			),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}	
	private function useTopicBriefDetailCurl( $briefid, $token ){

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiUrl.'keyword_reports/'.$briefid,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Bearer '.$token
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}	
	private function useTopicBriefDetailURLCurl( $briefid ){

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiUrl.'keyword_reports/'.$briefid,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}	

	private function getBriefUpdatedReportCurl( $briefid, $token, $content){
		//return json_encode(array($briefid, $token, $content));
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiUrl.'keyword_reports/'.$briefid,
			 	  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'PUT',
			  CURLOPT_POSTFIELDS => '{"editor_content_gdocs":"'.$content.'"}',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Bearer '.$token,
			    'Content-Type: application/json'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}

	private function getBriefUpdatedReportURLCurl( $briefid, $content){
		//return json_encode(array($briefid, $token, $content));
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiUrl.'keyword_reports/'.$briefid,
			 	  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'PUT',
			  CURLOPT_POSTFIELDS => '{"editor_content_gdocs":"'.$content.'"}',
			  CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;


	}
	private function getBriefsBySearchCurl( $search, $token ){
		if(empty($search)){
			$utAPIurl = $this->apiUrl.'keyword_reports/';
		}else{
			$utAPIurl = $this->apiUrl.'keyword_reports?filters=filter%5BBuser_id%5DD=all&filter%5Bsearch%5D='.$search; 
		}
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $utAPIurl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$token,
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}