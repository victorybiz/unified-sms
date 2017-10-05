<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class NullSMSDriver extends Driver
{		
	private $defaulSenderId;
	private $endpoint; 		
	private $apiKey; 
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct(array $config)
    {
        parent::__construct();
		
		$this->defaulSenderId 	= $config['default_sender_id'];
        $this->endpoint 		= 'null';
        $this->apiKey 			= $config['api_key'];
    }    
	
	/**
     * Get driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'Null SMS';
    }


    public function sendRequest(array $message) 
    {
		$request_response = [
				'status' => true,
				'statusCode' => '200',
				'statusDescription' => 'Success',
				'data' => [
							'to'=> 'null',
							'messageId'=> 'null',
						],
			];
		return json_encode($request_response);
    }   
    
}
