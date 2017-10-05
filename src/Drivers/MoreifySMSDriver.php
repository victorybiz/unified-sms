<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class MoreifySMSDriver extends Driver
{		
	private $endpoint; 		
	private $project; 	
	private $password;
	
	
	/**
     * Reposonse status code constants
     */
    const SUCCESS = true;
    const INVALID_URL_OR_RECIPIENT = 1202;
    const INVALID_AUTHENTICATION = 1101;
    const INSUFFICIENT_CREDIT = 1100;
	const INTERNAL_ERROR = 1706;
	
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
        $this->endpoint 		= 'https://mapi.moreify.com/api/v1/sendSms';
        $this->project 			= $config['project'];
        $this->password 		= $config['password'];
    }    
	
	/**
     * Get driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'Moreify SMS';
    }


    public function sendRequest(array $message) 
    {
		try {
			$to 		=  '+' . $message['to'];
			$text 		= $message['text'];			
			$params = [
                'project' => $this->project,
                'password' => $this->password,
                'phonenumber' => trim($to),
                'message' => trim($text)
            ];
            $url = $this->endpoint;
			
            $client = $this->guzzleClient; // Guzzle HTTP Client
			$response = $client->request('POST', $url, ['form_params' => $params, 'http_errors' => false]);
			$status_code = $response->getStatusCode();
			if ($status_code == 200 || $status_code == 400 || $status_code == 500) {
				$response = $response->getBody()->getContents();
				
                $result = json_decode($response, true); 
				
				if ($result['success'] == true) {
					$request_response = [
						'status' => true,
						'statusCode' => parent::SUCCESS,
						'statusDescription' => 'Success',
						'data' => [
									'to'=> $to,
									'messageId'=> $result['message-identifier'],
								],
					];					
				} else {
					
					switch ($result['errorCode']) 
					{
						case self::INVALID_URL_OR_RECIPIENT:
							$request_response = [
								'status' => false,
								'statusCode' => parent::INVALID_URL,
								'statusDescription' => 'The transmitted request does not include a phone number.',
								'data' => null,
							];
							break;
						case self::INVALID_AUTHENTICATION:
							$request_response = [
								'status' => false,
								'statusCode' => parent::INVALID_CREDENTIALS,
								'statusDescription' => 'Your project/password is wrong. Please verify the authentication data with your project settings.',
								'data' => null,
							];
							break;  
						case self::INSUFFICIENT_CREDIT:
							$request_response = [
								'status' => false,
								'statusCode' => parent::INSUFFICIENT_CREDIT,
								'statusDescription' => 'There is not enough balance on your account. Please login and topup your account.',
								'data' => null,
							];
							break;
						case self::INTERNAL_ERROR:
							$request_response = [
								'status' => false,
								'statusCode' => parent::INTERNAL_ERROR,
								'statusDescription' => 'An internal error in moreify occured, please try again later or contact our support.',
								'data' => null,
							];
							break;
						default:
							$request_response = [
								'status' => false,
								'statusCode' => parent::INTERNAL_ERROR,
								'statusDescription' => 'An error occurred: ' . $result['errorCode'] . ' - '.  $result['errorMessage'],
								'data' => null,
							];
					}
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
