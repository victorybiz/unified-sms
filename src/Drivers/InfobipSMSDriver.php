<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class NexmoSMSDriver extends Driver
{		
	private $defaulSenderId;
	private $endpoint; 		
	private $apiKey; 	
	private $apiSecret;
	
	
	/**
     * Reposonse status code constants
     */
    const SUCCESS = '0';
    const THROTTLED = 1;
    const MISSING_PARAMS = 2;
    const INVALID_PARAMS = 3;
    const INVALID_CREDENTIALS = 4;
    const INTERNAL_ERROR = 5;
    const INVALID_MESSAGE = 6;
    const NUMBER_BARRED = 7;
    const PARTNER_ACCOUNT_BARRED = 8;
    const PARTNER_QUOTA_EXCEEDED = 9;
    const ACCOUNT_NOT_ENABLED_FOR_REST = 11;
    const MESSAGE_TOO_LONG = 12;
    const COMMUNICATION_FALIED = 13;
    const INVALID_SIGNATURE = 14;
    const ILLEGAL_SENDER = 15;
    const INVALID_TTL = 16;
    const FACILITY_NOT_ALLOWED = 19;
    const INVALID_MESSAGE_CLASS = 20;
    const BAD_CALLBACK = 23;
    const NON_WHITELISTED_DESTINATION = 29;
    const INVALID_MISSING_MSISDN_PARAM = 34;
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
		$this->defaulSenderId 	= $config['default_sender_id'];
        $this->endpoint 		= 'https://rest.nexmo.com/sms/json';
        $this->apiKey 			= $config['api_key'];
        $this->apiSecret 		= $config['api_secret'];
        $this->callbackUrl 		= $config['callback_url'];
    }    
	
	/**
     * Get driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'Infobip SMS';
    }


    public function sendRequest(array $message) 
    {
		try {
			$sender		= isset($message['from']) ? $message['from'] : $this->defaulSenderId;
			$to 		= $message['to'];
			$text 		= $message['text'];			
			$params = [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'to' => trim($to),
                'from' => $sender,
                'text' => trim($text),
                'status-report-req' => '1',
                'callback' => $this->callbackUrl
            ];
            $url = $this->endpoint;
			
            $client = $this->guzzleClient; // Guzzle HTTP Client
			$response = $client->request('POST', $url, ['form_params' => $params, 'http_errors' => false]);
			$status_code = $response->getStatusCode();
			if ($status_code == 200) {
				$response = $response->getBody()->getContents();
				
                $result = json_decode($response, true); 
				$result_info = $result['messages'][0];
				
				switch ($result_info['status']) 
				{
                    case self::SUCCESS:
                        $request_response = [
							'status' => true,
							'statusCode' => parent::SUCCESS,
							'statusDescription' => 'Success',
							'data' => [
										'to'=> $result_info['to'],
										'messageId'=> $result_info['message-id'],
										'messageCost'=> $result_info['message-price'],
										'remainingBalance'=> $result_info['remaining-balance'],
										'network'=> $result_info['network'],
									],
						];
                        break;
					case self::MISSING_PARAMS:
                        $request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_URL,
							'statusDescription' => 'Your request is incomplete and missing some mandatory parameters.',
							'data' => null,
						];
                        break;
					case self::INVALID_PARAMS:
                        $request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_URL,
							'statusDescription' => 'The value of one or more parameters is invalid.',
							'data' => null,
						];
                        break;
                    case self::INVALID_CREDENTIALS:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_CREDENTIALS,
							'statusDescription' => 'The api_key / api_secret you supplied is either invalid or disabled.',
							'data' => null,
						];
                        break;  
					case self::INTERNAL_ERROR:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'There was an error processing your request in the Platform.',
							'data' => null,
						];
                        break;
                    default:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'An error occurred: ' . $result_info['status'] . ' - '.  $result_info['error-text'],
							'data' => null,
						];
                }
			} else {
				$request_response = [
					'status' => false,
					'statusCode' => parent::INTERNAL_ERROR,
					'statusDescription' => 'Driver request not found...',
					'data' => null,
				];
			}      
		} catch (Exception $e) {
			$request_response = [
				'status' => false,
				'statusCode' => parent::INTERNAL_ERROR,
				'statusDescription' => 'The following error occurred ' . $e->getMessage(),
				'data' => null,
			];
		}
		return json_encode($request_response);
    }   
    
}
