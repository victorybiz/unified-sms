<?php

namespace Victorybiz\UnifiedSMS;

use Victorybiz\UnifiedSMS\Drivers\Driver;

class UnifiedSMS
{   
	protected $config = array();
    protected $smsDriver = null;
    protected $voiceDriver = null;
	
   
    public function __construct(array $config)
    {
		$this->config = $config;
		try {			
			$default_sms_driver = $config['default_sms_driver'];
			$sms_config 		= $config['drivers'][$default_sms_driver]['sms'];
			$driverClass 		= 'Victorybiz\\UnifiedSMS\\Drivers\\'. ucfirst($default_sms_driver) . 'SMSDriver';
			$this->smsDriver = new $driverClass($sms_config);
			
			if (isset($config['default_voice_sms_driver']) && !empty($config['default_voice_sms_driver'])) {
				
				$default_voice_sms_driver 	= $config['default_voice_sms_driver'];
				if (isset($config['drivers'][$default_voice_sms_driver]['voice'])) {
					$voice_config 				= $config['drivers'][$default_voice_sms_driver]['voice'];
					$driverClass 				= 'Victorybiz\\UnifiedSMS\\Drivers\\'. ucfirst($default_voice_sms_driver) . 'VoiceDriver';
					$this->voiceDriver = new $driverClass($voice_config);
				} else {
					echo $default_voice_sms_driver . ' Voice configurations not set in unified-sms config file.';
				}				
			}
		} catch (Exception $e) {
			echo 'Config Error: ' . $e->getMessage();
		}		

    }
	
	public function sendSMS(array $msg) 
    {
		if (isset($msg['to']) && isset($msg['text'])) {
			
			//remove any + symbol before the phone number to allow the default drivers include it if required
			$msg['to'] = preg_replace("/\+/", '', $msg['to']); 
			// send request to service driver
			return $this->smsDriver->sendRequest($msg);
			
		} else {
			return [
					'status' => false,
					'statusCode' => Driver::MISSING_PARAMS,
					'statusDescription' => 'FROM, TO or TEXT is missing...',
					'data' => null,
				];
		}
	}   
	
	public function sendVoice(array $msg) 
    {
        //return $this->voiceDriver->sendRequest($msg);
	}  
	

    
}
