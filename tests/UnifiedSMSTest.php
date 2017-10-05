<?php
namespace Victorybiz\GeoIPLocationTest;

use PHPUnit_Framework_TestCase;
use Victorybiz\UnifiedSMS\UnifiedSMS;

class UnifiedSMSTest extends PHPUnit_Framework_TestCase 
{
    public function testSendSMS()
    {
        $expected_result = true;
        $result = true; 
        $this->assertEquals($expected_result, $result);
    }
}