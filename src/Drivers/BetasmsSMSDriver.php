<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class BetasmsSMSDriver extends Driver
{		
	private $defaulSenderId;
	private $endpoint; 		
	private $username; 	
	private $password;
	
	/**
     * Reposonse status code constants
     */
    const SUCCESS = 1701;
    const INVALID_URL_OR_RECIPIENT = 1705;
    const INVALID_USERNAME_PASSWORD = 1702;
    const INSUFFICIENT_CREDIT = 1704;
    const INSUFFICIENT_CREDIT_2 = 1025;
	const INTERNAL_ERROR = 1706;
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
		$this->endpoint 		= 'http://login.betasms.com/api/';
		$this->defaulSenderId 	= $config['default_sender_id'];
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
        return 'BetaSMS';
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
                'mobiles' => trim($to),
                'sender' => $sender,
                'message' => trim($text)
            ];
            $url = $this->endpoint . '?' . http_build_query($params);
			
            $client = $this->guzzleClient; // Guzzle HTTP Client
			$response = $client->request('GET', $url, ['http_errors' => false]);
			$status_code = $response->getStatusCode();
			if ($status_code == 200) {
				$response = $response->getBody()->getContents();
				switch ($response) 
				{
                    case self::SUCCESS:
                        $request_response = [
							'status' => true,
							'statusCode' => parent::SUCCESS,
							'statusDescription' => 'Success',
							'data' => [
										'to'=> $to,
										'messageId'=> null,
									],
						];
                        break;
					case self::INVALID_URL_OR_RECIPIENT:
                        $request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_URL,
							'statusDescription' => 'Invalid URL or tool many recipient',
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
					case self::INSUFFICIENT_CREDIT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INSUFFICIENT_CREDIT,
							'statusDescription' => 'Insufficient credit',
							'data' => null,
						];
                        break;                   
					case self::INSUFFICIENT_CREDIT_2:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INSUFFICIENT_CREDIT,
							'statusDescription' => 'Insufficient credit',
							'data' => null,
						];
                        break;    
					case self::INTERNAL_ERROR:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'Internal server error',
							'data' => null,
						];
                        break;
                    default:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'An error occurred: ' .  $response,
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
		$request_response['driver'] = "betasms";
		return json_encode($request_response);
    }   
    
}
