<?php

use PHPUnit\Framework\TestCase;
use ScormCloud\ScormEngineService;

class ScormEngineServiceTest extends TestCase
{
    public function testOriginString()
    {
        $service = new ScormEngineService('fakeAppId', 'fakeKey', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $string = $service->getOriginString();
        $this->assertInstanceOf('ScormCloud\ScormEngineService', $service);
        $this->assertEquals("testcompany.initialapplicationforrealm.1.0.0", $string);
    }

    public function isValidURL()
    {
        $service = new ScormEngineService('3GJ3Q90PRB', 'wVrVf5tAWiSIzutqapAYWnncysGsUkeu8n4vZnZd', 'Test Company', 'App Management App', '1.0.0');
        $this->assertTrue($service->isValidUrl());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage SCORM Cloud Error: 102 - Authentication info for application id fakeAppId not found. Likely an invalid application id
     */
    public function testFailureIsValidAccount()
    {
        $service = new ScormEngineService('fakeAppId', 'fakeKey', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $authResult = $service->isValidAccount();
    }

    public function testIsValidAccount()
    {
        $service = new ScormEngineService('4R7SARI28E', '41MMLXlRxLYv2OikL4sSMeW3zkA0BjlabnDwbiaL', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $authResult = $service->isValidAccount();
        $this->assertTrue($authResult);
    }
}
