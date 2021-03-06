<?php

namespace Victorybiz\UnifiedSMS\Drivers;

class NullVoiceDriver extends Driver
{		
	private $defaulCallerId;
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
		
		$this->defaulCallerId 	= $config['default_caller_id'];
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
        return 'Null Voice';
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
		$request_response['driver'] = "null";
		return json_encode($request_response);
    }   
    
}
