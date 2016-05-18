<?php
namespace ElasticEmail;
/**
* ElasticEmail: PHP ElasticEmail library
*
* github repository: https://github.com/mmendoza000/ElasticEmail
* @author : mmendoza000@gmail.com  mmendoza000@github
* @license :  GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007
* @version : 0.1.0
*
*/

require_once 'ElasticEmail/Messages.php';
require_once 'ElasticEmail/Templates.php';
require_once 'ElasticEmail/Exceptions.php';



class ElasticEmail {

    public $apikey;
    public $emailAccount;
    public $ch;
    public $root = 'https://api.elasticemail.com/'; // mailer/send
    public $ssl_root = 'ssl://api.elasticemail.com';
    public $debug = false;

    public static $error_map = array(
        "ValidationError" => "ElasticEmail_ValidationError",
        "Invalid_Key" => "ElasticEmail_Invalid_Key",
        "PaymentRequired" => "ElasticEmail_PaymentRequired",
        "Unknown_Subaccount" => "ElasticEmail_Unknown_Subaccount",
        "Unknown_Template" => "ElasticEmail_Unknown_Template",
        "ServiceUnavailable" => "ElasticEmail_ServiceUnavailable",
        "Unknown_Message" => "ElasticEmail_Unknown_Message",
        "Invalid_Tag_Name" => "ElasticEmail_Invalid_Tag_Name",
        "Invalid_Reject" => "ElasticEmail_Invalid_Reject",
        "Unknown_Sender" => "ElasticEmail_Unknown_Sender",
        "Unknown_Url" => "ElasticEmail_Unknown_Url",
        "Unknown_TrackingDomain" => "ElasticEmail_Unknown_TrackingDomain",
        "Invalid_Template" => "ElasticEmail_Invalid_Template",
        "Unknown_Webhook" => "ElasticEmail_Unknown_Webhook",
        "Unknown_InboundDomain" => "ElasticEmail_Unknown_InboundDomain",
        "Unknown_InboundRoute" => "ElasticEmail_Unknown_InboundRoute",
        "Unknown_Export" => "ElasticEmail_Unknown_Export",
        "IP_ProvisionLimit" => "ElasticEmail_IP_ProvisionLimit",
        "Unknown_Pool" => "ElasticEmail_Unknown_Pool",
        "NoSendingHistory" => "ElasticEmail_NoSendingHistory",
        "PoorReputation" => "ElasticEmail_PoorReputation",
        "Unknown_IP" => "ElasticEmail_Unknown_IP",
        "Invalid_EmptyDefaultPool" => "ElasticEmail_Invalid_EmptyDefaultPool",
        "Invalid_DeleteDefaultPool" => "ElasticEmail_Invalid_DeleteDefaultPool",
        "Invalid_DeleteNonEmptyPool" => "ElasticEmail_Invalid_DeleteNonEmptyPool",
        "Invalid_CustomDNS" => "ElasticEmail_Invalid_CustomDNS",
        "Invalid_CustomDNSPending" => "ElasticEmail_Invalid_CustomDNSPending",
        "Metadata_FieldLimit" => "ElasticEmail_Metadata_FieldLimit",
        "Unknown_MetadataField" => "ElasticEmail_Unknown_MetadataField"
    );

    public function __construct($apikey=null,$emailAccount=null) {
        if(!$apikey) $apikey = getenv('ELASTICEMAIL_API_KEY');
        if(!$apikey) $apikey = $this->readConfigs();
        if(!$apikey) throw new ElasticEmail_Error('You must provide a ElasticEmail API key');
        if(!$emailAccount) throw new ElasticEmail_Error('You must provide a ElasticEmail email account');

        $this->apikey = $apikey;
        $this->emailAccount = $emailAccount;



        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'ElasticEmail-PHP/0.1.0');
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);
        // fixing error ElasticEmail_HttpError
		    curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->root = rtrim($this->root, '/') . '/';

        $this->templates = new ElasticEmail_Templates($this);
        $this->messages = new ElasticEmail_Messages($this);

    }

    public function __destruct() {
        curl_close($this->ch);
    }

    public function call($url, $params) {



        $ch = $this->ch;

        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $this->root . $url );
        curl_setopt($ch, CURLOPT_POST, 1);


      	// Set parameter data to POST fields
      	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        // Header data
        $header = "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".strlen($params)."\r\n\r\n";

        // Set header
        curl_setopt($ch, CURLOPT_HEADER, $header);


        // Set to receive server response
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      	// Set cURL to verify SSL
      	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
      	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

      	// Set the path to the certificate used by Elastic Mail API
      	//curl_setopt($ch, CURLOPT_CAINFO, getcwd()."/ElasticEmail/DOWNLOADED_CERTIFICATE.CRT");
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);



        $start = microtime(true);
        $this->log('Call to ' . $this->root . $url . ' params:' . $params);
        if($this->debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }


        $response_body = curl_exec($ch);
        $info = curl_getinfo($ch);
        $time = microtime(true) - $start;
        if($this->debug) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Got response: ' . $response_body);

        if(curl_error($ch)) {
            throw new ElasticEmail_HttpError("API call to $url failed: " . curl_error($ch));
        }

        $result = $response_body;
        //$result = json_decode($response_body, true);
        //if($result === null) throw new ElasticEmail_Error('We were unable to decode the JSON response from the ElastucEmail API: ' . $response_body);

        if(floor($info['http_code'] / 100) >= 4) {
            throw $this->castError($result);
        }

        return $result;
    }

    public function readConfigs() {
        $paths = array('~/.elasticemail.key', '/etc/elasticemail.key');
        foreach($paths as $path) {
            if(file_exists($path)) {
                $apikey = trim(file_get_contents($path));
                if($apikey) return $apikey;
            }
        }
        return false;
    }

    public function castError($result) {
        if($result['status'] !== 'error' || !$result['name']) throw new ElasticEmail_Error('We received an unexpected error: ' . json_encode($result));

        $class = (isset(self::$error_map[$result['name']])) ? self::$error_map[$result['name']] : 'ElasticEmail_Error';
        return new $class($result['message'], $result['code']);
    }

    public function log($msg) {
        if($this->debug) error_log($msg);
    }
}
