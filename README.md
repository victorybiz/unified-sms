# unified-sms
Unified SMS library package for Laravel 5 and PHP (Non-Laravel) to send text messages through multiple swappable drivers.

## Supported Drivers and Services
It currently ships with the following drivers:

* `null` - Null (SMS, Voice SMS only) - for test purposes, this driver does nothing.
* `routemobile` - [RouteMobile - RouteSMS](http://routemobile.com/) (SMS only)
* `nexmo` - [Nexmo](http://nexmo.com/) (SMS only)
* `moreify` - [Moreify](http://moreify.com/) (SMS only)
* `betasms` - [BetaSMS](http://betasms.com/) (SMS only)

## Installation
Install using composer, from the command line run:

```bash
$ composer require victorybiz/unified-sms
```
### Laravel Project
Alternatively, you can add `"victorybiz/unified-sms": "~1.0"` to your composer.json file's `require` section and 
then run `$ composer update`.

Once installed you need to register the service provider with the application. Open up `config/app.php` and locate the `providers` key.

```php
'providers' => [

    Victorybiz\UnifiedSMS\UnifiedSMSServiceProvider::class,

]
```
And add the UnifiedSMS alias to config/app.php:
```php
'aliases' => [

	'UnifiedSMS' => Victorybiz\UnifiedSMS\Facades\UnifiedSMSFacade::class,

]
```
You must publish the package's configuration files, (unified-sms.php also published with this)
And add the UnifiedSMS alias to config/app.php:
```php
php artisan vendor:publish --tag=unified-sms
```
Open up `config/unified-sms.php` use the `env` variables to set your DEFAULT DRIVER and API Credentials.

### Usage in Laravel Project
Please use the `UnifiedSMS` Facade
```php
use UnifiedSMS;
```
You're good to go, send sms
```php
$msg = [
			'from' => 'Your sender ID here',
			'to' => 'The recipent mobile number, international format without the leading plus (+)',
			'text' => 'Your text message here.',
	   ];
$response = UnifiedSMS::sendSMS($msg);
```


### Usage in PHP (Non-Laravel) Project
Require the vendor autoload file in your php script.

```php
require_once 'path/to/vendor/autoload.php';
```
Create a `unified_sms_config.php` file anywhere in your project directory.
Enter the following block into your `unified_sms_config.php` and set your DEFAULT DRIVER and API Credentials.
```php
$unified_sms_config = [
    /*
    |--------------------------------------------------------------------------
    | Default SMS driver
    |--------------------------------------------------------------------------
    | This option controls the default SMS driver to use.
    |
    | Supported: "null", "routemobile", "nexmo", "moreify", "betasms"
    */
    'default_sms_driver' => 'null',
	
	/*
    |--------------------------------------------------------------------------
    | Default VOICE SMS driver
    |--------------------------------------------------------------------------
    | This option controls the default Voice SMS driver to use.
    |
    | Supported: "null"
    */
    'default_voice_sms_driver' => 'null',
	
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
				'default_sender_id' => 'INFO'),
				'server' => '',
				'port' => '',
				'username' => '',
				'password' => '',
			],          
		],
		'nexmo' => [
			'sms' => [
				'default_sender_id' => 'INFO',
				'api_key' => ''),
				'api_secret' => '',
				'callback_url' => null), // The webhook endpoint the delivery receipt for this sms is sent to. 
									// If set, it overrides the webhook endpoint you set in Dashboard 
			], 
		],
		'moreify' => [
			'sms' => [
				'project' => '',
				'password' => '',
			], 
		],
		'betasms' => [
			'sms' => [
				'default_sender_id' => 'INFO',
				'username' => '',
				'password' => '',
			], 
		],
	],
];
```
Require the config file in your php script.
```php
require_once 'path/to/unified_sms_config.php';
```

```php
use Victorybiz\UnifiedSMS\UnifiedSMS;

$unifiedSMS = new UnifiedSMS($unified_sms_config); 
```
Alternatively
```php
$unifiedSMS = new \Victorybiz\UnifiedSMS\UnifiedSMS($unified_sms_config);
```
You're good to go, send sms
```php
$msg = [
			'from' => 'Your sender ID here',
			'to' => 'The recipent mobile number, international format without the leading plus (+)',
			'text' => 'Your text message here.',
	   ];
$response = $unifiedSMS->sendSMS($msg);
```
### Response from Drivers
On successful, `$response` will return json data
```json
{
	"status":true,
	"statusCode":200,
	"statusDescription":"Success",
	"data": {
		"to":"Recipient phone number",
		"messageId":"The Message ID from the driver service provider",
		"_comment":"May include additional data but depends on the response from the driver service provider"
	}
}
```
On failure, `$response` will return json data
```json
{
	"status":false,
	"statusCode":"status error code here",
	"statusDescription":"Status description / message",
	"data":null
}
```
The failure status codes are
```php
200				Success
1001			Invalid URL or Missing Params
1002			Invalid credentials
1003			Invalid recipient
1004			Invalid sender
1005			Invalid message
1006			Invalid message type
1007			Invalid delivery
1008			Insufficient credit
1009			Response timeout
1010			Internal error
```

## Bug Reports and Issue tracking 

Kindly make use of the issue tracker for bug reports, feature request, additional web service request and security issues. 

## License
[MIT](http://opensource.org/licenses/MIT) 





