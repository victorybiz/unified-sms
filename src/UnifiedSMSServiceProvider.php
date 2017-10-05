<?php

namespace Victorybiz\UnifiedSMS;

use Illuminate\Support\ServiceProvider;

class UnifiedSMSServiceProvider extends ServiceProvider
{
	/*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;
	
	
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('unified_sms.php'),
        ], 'unified-sms'); // --tag=unified-sms
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('unified-sms', function ($app) {
			$config 			= $app['config'];
			$unified_sms_config	= $config['unified_sms'];
			if (!is_array($unified_sms_config)) {
				echo 'Please publish UnifiedSMS config file: "php artisan vendor:publish --tag=unified-sms"';				
			}
            return new UnifiedSMS($unified_sms_config); // Or new \Victorybiz\UnifiedSMS\UnifiedSMS when no proper namespace
        });
    }
	
	/**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['unified-sms'];
    }
}
