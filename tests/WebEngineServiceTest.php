<?php

use PHPUnit\Framework\TestCase;
use ScormCloud\ScormEngineService;

class ScormEngineServiceTest extends TestCase
{
    public function testOriginString()
    {
        $service = new ScormEngineService('fakeAppId', 'fakeKey', 'Test Company', 'Initial Application For Realm', '1.0.0');
        $string = $service->getOriginString();
        $this->assertInstanceOf('ScormCloud\ScormEngineService', $service);
        $this->assertEquals("testcompany.initialapplicationforrealm.1.0.0", $string);
    }

    public function isValidURL()
    {
        $service = new ScormEngineService(AppManagementKey, AppManagementSecret, CompanyName, AppManagementName, ScormCloudVersion);
        $this->assertTrue($service->isValidUrl());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage SCORM Cloud Error: 102 - Authentication info for application id fakeAppId not found. Likely an invalid application id
     */
    public function testFailureIsValidAccount()
    {
        $service = new ScormEngineService('fakeAppId', 'fakeKey', CompanyName, AppName, ScormCloudVersion);
        $authResult = $service->isValidAccount();
    }

    public function testIsValidAccount()
    {
        $service = new ScormEngineService(AppKey, AppSecret, CompanyName, AppName, ScormCloudVersion);
        $authResult = $service->isValidAccount();
        $this->assertTrue($authResult);
    }
}
