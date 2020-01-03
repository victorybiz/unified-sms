<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class MultitexterSMSDriver extends Driver
{		
	private $defaulSenderId;
	private $endpoint; 		
	private $email; 	
	private $password;
	private $force_dnd;
	
	/**
     * Reposonse status code constants
     */
    const SUCCESS = 1;
    const INVALID_PARAMETERS = -2;
    const ACCOUNT_SUSPENDED = -3;
    const INVALID_DISPLAY_NAME = -4;
    const INVALID_MESSAGE_CONTENT = -5;
	const INVALID_RECIPIENT = -6;
	const INSUFFICIENT_UNIT = -7;
	const UNKNOWN_ERROR = -10;
	const UNAUTHENTICATED = 401;
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
		$this->endpoint 		= 'https://app.multitexter.com/v2/app/sms';
		$this->defaulSenderId 	= $config['default_sender_id'];
        $this->email 		    = $config['email'];
        $this->password 		= $config['password'];
        $this->force_dnd 		= $config['force_dnd'];
    }    
	
	/**
     * Get driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'MultiTexter SMS';
    }


    public function sendRequest(array $message) 
    {
		try {
			$sender		= isset($message['from']) ? $message['from'] : $this->defaulSenderId;
			$to 		= $message['to'];
			$text 		= $message['text'];			
			$params = [
                'email' => $this->email,
                'password' => $this->password,
                'forcednd' => $this->force_dnd,
                'recipients' => trim($to),
                'sender_name' => $sender,
                'message' => trim($text)
            ];
            $url = $this->endpoint;
            
            $client = $this->guzzleClient; // Guzzle HTTP Client
			$response = $client->request('POST', $url, ['form_params' => $params, 'http_errors' => false]);
			$status_code = $response->getStatusCode();
			if ($status_code == 200) {
                $response = $response->getBody()->getContents();
                
                $result = json_decode($response, true); 

				switch ($result_info['status']) 
				{
                    case self::SUCCESS:
                        $request_response = [
							'status' => true,
							'statusCode' => parent::SUCCESS,
							'statusDescription' => 'Message sent.',
							'data' => [
										'to'=> $to,
										'messageId'=> null,
									],
						];
                        break;
					case self::INVALID_PARAMETERS:
                        $request_response = [
							'status' => false,
							'statusCode' => parent::MISSING_PARAMS,
							'statusDescription' => 'Invalid Parameter.',
							'data' => null,
						];
                        break;
                    case self::ACCOUNT_SUSPENDED:
						$request_response = [
							'status' => false,
							'statusCode' => parent::ACCOUNT_SUSPENDED,
							'statusDescription' => 'Account suspended due to fraudulent message.',
							'data' => null,
						];
                        break;	
					case self::INVALID_DISPLAY_NAME:
						$request_response = [
							'status' => false,
							'statusCode' => parent::MISSING_PARAMS,
							'statusDescription' => 'Invalid Display name.',
							'data' => null,
						];
                        break;                   
					case self::INVALID_MESSAGE_CONTENT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_MESSAGE,
							'statusDescription' => 'Invalid Message content.',
							'data' => null,
						];
                        break;    
					case self::INVALID_RECIPIENT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INVALID_RECIPIENT,
							'statusDescription' => 'Invalid recipient.',
							'data' => null,
						];
                        break;
					case self::INSUFFICIENT_UNIT:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INSUFFICIENT_CREDIT,
							'statusDescription' => 'Insufficient unit.',
							'data' => null,
						];
                        break;
					case self::UNKNOWN_ERROR:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'Unknown error.',
							'data' => null,
						];
                        break;
					case self::UNAUTHENTICATED:
						$request_response = [
							'status' => false,
							'statusCode' => parent::INTERNAL_ERROR,
							'statusDescription' => 'Unauthenticated.',
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
