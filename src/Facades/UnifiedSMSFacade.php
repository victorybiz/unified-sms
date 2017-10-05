<?php

namespace Victorybiz\UnifiedSMS\Facades;

use Illuminate\Support\Facades\Facade;

class UnifiedSMSFacade extends Facade
{
    /**
     * Get the registered name / binding of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'unified-sms'; // the IoC container binding name registered in Service Provider 
    }
}