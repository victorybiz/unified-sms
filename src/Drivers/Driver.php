<?php

namespace Victorybiz\UnifiedSMS\Drivers;

use GuzzleHttp\Client;
//use GuzzleHttp\Exception\TransferException;

class Driver
{

    protected $guzzleClient;
	
	const SUCCESS = 200;
    const INVALID_URL = 1001;
	const MISSING_PARAMS = 1001;
    const INVALID_CREDENTIALS = 1002;
	const INVALID_RECIPIENT = 1003;
	const INVALID_SENDER = 1004;
    const INVALID_MESSAGE = 1005;
	const INVALID_TYPE = 1006; 
    const INVALID_DELIVERY = 1007;
    const INSUFFICIENT_CREDIT = 1008;
	const RESPONSE_TIMEOUT = 1009;
    const INTERNAL_ERROR = 1010;
    
    public function __construct()
    {
        $this->guzzleClient = new Client();
    }
}