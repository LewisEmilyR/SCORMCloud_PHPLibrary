<?php

use PHPUnit\Framework\TestCase;
use ScormCloud\ScormEngineService;

class LrsAccountServiceTest extends TestCase
{
    public function testListActivityProviders()
    {
        $service = new ScormEngineService(AppKey, AppSecret, CompanyName, AppName, ScormCloudVersion);
        $list = $service->lrsAccountService()->listActivityProviders();
        // NOTE: an empty list will also pass this test.
        $this->assertContainsOnlyInstancesOf(ScormCloud\LRSAccount::class, $list);
    }

    public function testCreateActivityProvider()
    {
        $service = new ScormEngineService(AppKey, AppSecret, CompanyName, AppName, ScormCloudVersion);
        $provider = $service->lrsAccountService()->createActivityProvider();
        return $provider;
    }


    /**
     * @depends testCreateActivityProvider
     */
    public function testEditActivityProvider($provider)
    {
        $service = new ScormEngineService(AppKey, AppSecret, CompanyName, AppName, ScormCloudVersion);
        $newProvider = $service->lrsAccountService()->editActivityProvider($provider->getKey(), null, null, "Edited Provider", AppKey);
        $this->assertEquals("Edited Provider", $newProvider->getLabel());
        $this->assertEquals(AppKey, $newProvider->getAllowedEndpoints());
        return $newProvider;
    }

    /**
     * @depends testEditActivityProvider
     */
    public function testDeleteActivityProvider($provider)
    {
        $service = new ScormEngineService(AppKey, AppSecret, CompanyName, AppName, ScormCloudVersion);
        $response = $service->lrsAccountService()->deleteActivityProvider($provider->getKey());
        //NOTE: If the deletion fails, expect an exception.
        $this->assertTrue($response);
    }
}
