<?php

/**
*
* handypress_google_cloudprint
*
*
* $this->cloudprint = new handypress_google_cloudprint();
* $this->cloudprint->display($file_dir);
*
* 
**/
if ( ! class_exists('handypress_google_cloudprint') ) {
  
class handypress_google_cloudprint {

  public $config = array();
  
  function __construct( $params = null ){

    $this->config['redirectConfig'] = array(
        'client_id'   => '562961024279-6v99clq282lsmcsl7jun97h9tcbvmgju.apps.googleusercontent.com',
        'redirect_uri'  => 'http://absurde.handypress.io/wp-content/plugins/HANDYPRINTER/lib/php-google-cloud-print/oAuthRedirect.php',
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/cloudprint',
    );
    
    $this->config['authConfig'] = array(
        'code' => '',
        'client_id'   => '562961024279-6v99clq282lsmcsl7jun97h9tcbvmgju.apps.googleusercontent.com',
        'client_secret' => '6qhUqA_JlBl3efdD66mLk_s5',
        'redirect_uri'  => 'http://absurde.handypress.io/wp-content/plugins/HANDYPRINTER/lib/php-google-cloud-print/oAuthRedirect.php',
        "grant_type"    => "authorization_code"
    );
    
    $this->config['offlineAccessConfig'] = array(
        'access_type' => 'offline'
    );
    
    $this->config['refreshTokenConfig'] = array(
        
        'refresh_token' => "",
        'client_id' => $this->config['authConfig']['client_id'],
        'client_secret' => $this->config['authConfig']['client_secret'],
        'grant_type' => "refresh_token" 
    );
    
    $this->config['urlconfig'] = array( 
        'authorization_url'   => 'https://accounts.google.com/o/oauth2/auth',
        'accesstoken_url'     => 'https://accounts.google.com/o/oauth2/token',
        'refreshtoken_url'      => 'https://www.googleapis.com/oauth2/v3/token'
    );

  }

  public function cloudprint( $file_dir ){

    // Create object
    $gcp = new GoogleCloudPrint();

    // Replace token you got in offlineToken.php
    $this->config['refreshTokenConfig']['refresh_token'] = '1/OwAi-GoAJf4971rz2to2MW2_nYZhQbJ7PTvni0F5b3I';

    $token = $gcp->getAccessTokenByRefreshToken( $this->config['urlconfig']['refreshtoken_url'], http_build_query($this->config['refreshTokenConfig']) );

    $gcp->setAuthToken($token);

    $printers = $gcp->getPrinters();
    //print_r($printers);

    $printerid = "";

    if(count($printers)==0) {
      
      return "Could not get printers";
      
    } else {
      
      $printerid = $printers[0]['id']; // Pass id of any printer to be used for print
      
      // Send document to the printer
      $resarray = $gcp->sendPrintToPrinter($printerid, "Printing Doc using Google Cloud Printing", $file_dir, "application/pdf");
      
      if($resarray['status']==true) {
        
        return "Document has been sent to printer and should print shortly.";
    
      } else {
        
        return "An error occured while printing the doc. Error code:".$resarray['errorcode']." Message:".$resarray['errormessage'];
      
      }
    
    }

  }

}

/*
PHP implementation of Google Cloud Print
Author, Yasir Siddiqui

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class GoogleCloudPrint {
  
  const PRINTERS_SEARCH_URL = "https://www.google.com/cloudprint/search";
  const PRINT_URL = "https://www.google.com/cloudprint/submit";
  
  private $authtoken;
  private $httpRequest;
  private $refreshtoken;
  
  /**
   * Function __construct
   * Set private members varials to blank
   */
  public function __construct() {
    
    $this->authtoken = "";
    $this->httpRequest = new HttpRequest();
  }
  
  /**
   * Function setAuthToken
   *
   * Set auth tokem
   * @param string $token token to set
   */
  public function setAuthToken($token) {
    $this->authtoken = $token;
  }
  
  /**
   * Function getAuthToken
   *
   * Get auth tokem
   * return auth tokem
   */
  public function getAuthToken() {
    return $this->authtoken;
  }
  
  
  /**
   * Function getAccessTokenByRefreshToken
   *
   * Gets access token by making http request
   * 
   * @param $url url to post data to
   * 
   * @param $post_fields post fileds array
   * 
   * return access tokem
   */
  
  public function getAccessTokenByRefreshToken($url,$post_fields) {
    $responseObj =  $this->getAccessToken($url,$post_fields);
    return $responseObj->access_token;
  }
  
  
  /**
   * Function getAccessToken
   *
   * Makes Http request call
   * 
   * @param $url url to post data to
   * 
   * @param $post_fields post fileds array
   * 
   * return http response
   */
  public function getAccessToken($url,$post_fields) {
    
    $this->httpRequest->setUrl($url);
    $this->httpRequest->setPostData($post_fields);
    $this->httpRequest->send();
    $response = json_decode($this->httpRequest->getResponse());
    return $response;
  }
  
  /**
   * Function getPrinters
   *
   * Get all the printers added by user on Google Cloud Print. 
   * Follow this link https://support.google.com/cloudprint/answer/1686197 in order to know how to add printers
   * to Google Cloud Print service.
   */
  public function getPrinters() {
    
    // Check if we have auth token
    if(empty($this->authtoken)) {
      // We don't have auth token so throw exception
      throw new Exception("Please first login to Google");
    }
    
    // Prepare auth headers with auth token
    $authheaders = array(
    "Authorization: Bearer " .$this->authtoken
    );
    
    $this->httpRequest->setUrl(self::PRINTERS_SEARCH_URL);
    $this->httpRequest->setHeaders($authheaders);
    $this->httpRequest->send();
    $responsedata = $this->httpRequest->getResponse();
    // Make Http call to get printers added by user to Google Cloud Print
    $printers = json_decode($responsedata);
    // Check if we have printers?
    if(is_null($printers)) {
      // We dont have printers so return balnk array
      return array();
    }
    else {
      // We have printers so returns printers as array
      return $this->parsePrinters($printers);
    }
    
  }
  
  /**
   * Function sendPrintToPrinter
   * 
   * Sends document to the printer
   * 
   * @param Printer id $printerid    // Printer id returned by Google Cloud Print service
   * 
   * @param Job Title $printjobtitle // Title of the print Job e.g. Fincial reports 2012
   * 
   * @param File Path $filepath      // Path to the file to be send to Google Cloud Print
   * 
   * @param Content Type $contenttype // File content type e.g. application/pdf, image/png for pdf and images
   */
  public function sendPrintToPrinter($printerid,$printjobtitle,$filepath,$contenttype) {
    
  // Check if we have auth token
    if(empty($this->authtoken)) {
      // We don't have auth token so throw exception
      throw new Exception("Please first login to Google by calling loginToGoogle function");
    }
    // Check if prtinter id is passed
    if(empty($printerid)) {
      // Printer id is not there so throw exception
      throw new Exception("Please provide printer ID"); 
    }
    // Open the file which needs to be print
    $handle = fopen($filepath, "rb");
    if(!$handle)
    {
      // Can't locate file so throw exception
      throw new Exception("Could not read the file. Please check file path.");
    }
    // Read file content
    $contents = fread($handle, filesize($filepath));
    fclose($handle);
    
    // Prepare post fields for sending print
    $post_fields = array(
        
      'printerid' => $printerid,
      'title' => $printjobtitle,
      'contentTransferEncoding' => 'base64',
      'content' => base64_encode($contents), // encode file content as base64
      'contentType' => $contenttype   
    );
    // Prepare authorization headers
    $authheaders = array(
      "Authorization: Bearer " . $this->authtoken
    );
    
    // Make http call for sending print Job
    $this->httpRequest->setUrl(self::PRINT_URL);
    $this->httpRequest->setPostData($post_fields);
    $this->httpRequest->setHeaders($authheaders);
    $this->httpRequest->send();
    $response = json_decode($this->httpRequest->getResponse());
    
    // Has document been successfully sent?
    if($response->success=="1") {
      
      return array('status' =>true,'errorcode' =>'','errormessage'=>"");
    }
    else {
      
      return array('status' =>false,'errorcode' =>$response->errorCode,'errormessage'=>$response->message);
    }
  }
  
  /**
   * Function parsePrinters
   * 
   * Parse json response and return printers array
   * 
   * @param $jsonobj // Json response object
   * 
   */
  private function parsePrinters($jsonobj) {
    
    $printers = array();
    if (isset($jsonobj->printers)) {
      foreach ($jsonobj->printers as $gcpprinter) {
        $printers[] = array('id' =>$gcpprinter->id,'name' =>$gcpprinter->name,'displayName' =>$gcpprinter->displayName,
                'ownerName' => $gcpprinter->ownerName,'connectionStatus' => $gcpprinter->connectionStatus,
                );
      }
    }
    return $printers;
  }
}








/*
Simple Http request class
Author, Yasir Siddiqui

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
class HttpRequest {
        
        public $httpResponse;
        public $ch;
        
        /**
   * Function __construct
   * Set member variables
   * @param url $url  // Url to send http request to
   */
        public function __construct($url = null) {
            
            // Initialize curl
            $this->ch = curl_init();
     
            curl_setopt( $this->ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt( $this->ch, CURLOPT_HEADER,false);
            curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt( $this->ch, CURLOPT_HTTPAUTH,CURLAUTH_ANY);
      
       if(isset($url)) {
    $this->setUrl($url);
      }
        }
  
  /**
   * Function setUrl
   * Set http request url
   * @param string $url  // http request url
   */
  public function setUrl($url) {
    curl_setopt( $this->ch, CURLOPT_URL, $url );
  }

        /**
   * Function setPostData
   * Set data to be posted to the url
   * @param array $params  // Key value pairs of data to be posted
   */
        public function setPostData( $params ) {
            
            curl_setopt( $this->ch, CURLOPT_POST, true );
            curl_setopt ( $this->ch, CURLOPT_POSTFIELDS,$params);
        }
  
   /**
   * Function setHeaders
   * Set http request headers
   * @param array $headers  // array containing headers
   */
  public function setHeaders($headers) {
    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
  }
        
        /**
   * Function send
   * Send http request
   * return void
   */
        public function send() {
            // execute curl
            $this->httpResponse = curl_exec( $this->ch );
        }
        
        /**
   * Function getResponse
   * return response of last http request sent
   * return http response
   */
        public function getResponse() {
            return $this->httpResponse;
        }
        
        /**
   * Function __destruct
   * class destructor
   */
        public function __destruct() {
            curl_close($this->ch);
        }
}
}
