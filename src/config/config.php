<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS driver
    |--------------------------------------------------------------------------
    | This option controls the default SMS driver to use.
    |
    | Supported: "routemobile"
    */
    'default_sms_driver' => env('SMS_DRIVER', 'null'),
	
	/*
    |--------------------------------------------------------------------------
    | Default VOICE SMS driver
    |--------------------------------------------------------------------------
    | This option controls the default Voice SMS driver to use.
    |
    | Supported: "routemobile"
    */
    'default_voice_sms_driver' => env('VOICE_SMS_DRIVER', 'null'),
	
    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    | Here you can define the settings for each driver. 
    */
    'drivers' => [
		'null' => [
            'sms' => [
				'default_sender_id' => 'Null',
				'api_key' => 'null'
			],
			'voice' => [
				'default_caller_id' => '+1000',
				'api_key' => 'null',
			],            
        ],
        'routemobile' => [
			'sms' => [
				'default_sender_id' => env('ROUTESMS_SENDER_ID', 'INFO'),
				'server' => env('ROUTESMS_SERVER', ''),
				'port' => env('ROUTESMS_PORT', '8080'),
				'username' => env('ROUTESMS_USERNAME', ''),
				'password' => env('ROUTESMS_PASSWORD', ''),
			],          
        ],
		'nexmo' => [
			'sms' => [
				'default_sender_id' => env('NEXMO_SENDER_ID', 'INFO'),
				'api_key' => env('NEXMO_API_KEY', ''),
				'api_secret' => env('NEXMO_API_SECRET', ''),
				'callback_url' => env('NEXMO_CALLBACK_URL', null), // The webhook endpoint the delivery receipt for this sms is sent to. 
																	// If set, it overrides the webhook endpoint you set in Dashboard 
			], 
        ],
		'moreify' => [
			'sms' => [
				'project' => env('MOREIFY_PROJECT', ''),
				'password' => env('MOREIFY_PASSWORD', ''),
			], 
        ],
		'betasms' => [
			'sms' => [
				'default_sender_id' => env('BETASMS_SENDER_ID', 'INFO'),
				'username' => env('BETASMS_USERNAME', ''),
				'password' => env('BETASMS_PASSWORD', ''),
			], 
        ],
		'multitexter' => [
			'sms' => [
				'default_sender_id' => env('MULTITEXTER_SENDER_ID', 'INFO'),
				'email' => env('MULTITEXTER_EMAIL', ''),
				'password' => env('MULTITEXTER_PASSWORD', ''),
				'force_dnd' => env('MULTITEXTER_FORCE_DND', true),
			], 
        ],
    ],
];