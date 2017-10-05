<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class RoutemobileSMSDriver extends Driver
{		
	private $defaulSenderId;
	private $endpoint; 		
	private $username; 	
	private $password;
	
	
	/**
     * Reposonse status code constants
     */
    const SUCCESS = 1701;
    const INVALID_URL = 1702;
    const INVALID_USERNAME_PASSWORD = 1703;
    const INVALID_TYPE = 1704;
    const INVALID_MESSAGE = 1705;
    const INVALID_RECIPIENT = 1706;
    const INVALID_SENDER = 1707;
    const INVALID_DLR = 1708;
    const USER_VALIDATION_FAILED = 1709;
    const INTERNAL_ERROR = 1710;
    const INSUFFICIENT_CREDIT = 1025;
    const RESPONSE_TIMEOUT = 1715;
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
		$this->defaulSenderId 	= $config['default_sender_id'];
        $this->endpoint 		= 'http://' . $config['server'] . ':' . $config['port'] . '/bulksms/bulksms';
        $this->username 		= $config['username'];
        $this->password 		= $config['password'];
    }    
	
	/**
     * Get driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'RouteMobile (RouteSMS)';
    }


    public function sendRequest(array $message) 
    {
		try {
			$sender		= isset($message['from']) ? $message['from'] : $this->defaulSenderId;
			$to 		= $message['to'];
			$text 		= $message['text'];			
			$params = [
                'username' => $this->username,
                'password' => $this->password,
                'type' => '0',
                'dlr' => '1',
                'destination' => trim($to),
                'source' => $sender,
                'message' => trim($text)
            ];
            $url = $this->endpoint . '?' . http_build_query($params);
			
            $client = $this->guzzleClient; // Guzzle HTTP Client
			$response = $client->request('GET', $url);
			$status_code = $response->getStatusCode();
			if ($status_code == 200) {
				$response = $response->getBody()->getContents();
                $result = explode('|', $response);				
				switch ($result[0]) 
				{
                    case self::SUCCESS:
                        $request_response = [
							'status' => true,
							'statusCode' => parent::SUCCESS,
							'statusDescription' => 'Success',
							'data' => [
										'to'=> $result[1],
										'messageId'=> $result[2],
									],
						];
                        break;
					case self::INVALID_URL:
                        $request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_URL,
							'statusDescription' => 'Invalid URL',
							'data' => null,
						];
                        break;
                    case self::INVALID_USERNAME_PASSWORD:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_CREDENTIALS,
							'statusDescription' => 'Invalid username or password supplied',
							'data' => null,
						];
                        break;					
                    case self::USER_VALIDATION_FAILED:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_CREDENTIALS,
							'statusDescription' => 'User validation error',
							'data' => null,
						];
                        break;                    
					case self::INVALID_RECIPIENT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_RECIPIENT,
							'statusDescription' => 'Invalid recipient. Recipient must be numeric',
							'data' => null,
						];
                        break;
                    case self::INVALID_SENDER:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_SENDER,
							'statusDescription' => 'Invalid sender. Sender must not be more than 11 characters',
							'data' => null,
						];
                        break;
                    case self::INVALID_MESSAGE:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_MESSAGE,
							'statusDescription' => 'Invalid message. Message contains invalid characters',
							'data' => null,
						];
                        break;
					case self::INVALID_TYPE:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_TYPE,
							'statusDescription' => 'Invalid type supplied',
							'data' => null,
						];
                        break;
                    case self::INVALID_DLR:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_DELIVERY,
							'statusDescription' => 'Invalid dlr supplied',
							'data' => null,
						];
                        break;
					case self::INSUFFICIENT_CREDIT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INSUFFICIENT_CREDIT,
							'statusDescription' => 'Insufficient credit',
							'data' => null,
						];
                        break;                   
					case self::RESPONSE_TIMEOUT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::RESPONSE_TIMEOUT,
							'statusDescription' => 'Response timeout',
							'data' => null,
						];
                        break;
					case self::INTERNAL_ERROR:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'Internal error',
							'data' => null,
						];
                        break;
                    default:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'An error occurred: ' .  $result[0],
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
		$request_response['driver'] = "routemobile";
		return json_encode($request_response);
    }   
    
}
