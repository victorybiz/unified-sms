<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class RoutemobileVoiceDriver extends Driver
{		
	private $defaulCallerId;
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
		
		$this->defaulCallerId 	= $config['default_caller_id'];
        $this->endpoint 		= 'http://' . $config['server'] . ':' . $config['port'] . '/httpApi/genCalls.php';
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
        return 'RouteMobile (RouteVoice)';
    }


    public function sendRequest(array $message) 
    {
		$request_response = [
			'status' => false,
			'statusCode' => '1010',
			'statusDescription' => 'Voice Driver: RouteMobile (RouteVoice) not available.',
			'data' => null,
		];
		$request_response['driver'] = "routemobile";
		return json_encode($request_response);
    }   
    
}