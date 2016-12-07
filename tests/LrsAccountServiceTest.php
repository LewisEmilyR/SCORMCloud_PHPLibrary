<?php

use PHPUnit\Framework\TestCase;
use ScormCloud\ScormEngineService;

class LrsAccountServiceTest extends TestCase
{
    public function testListActivityProviders()
    {
        $service = new ScormEngineService('4R7SARI28E', '41MMLXlRxLYv2OikL4sSMeW3zkA0BjlabnDwbiaL', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $list = $service->lrsAccountService()->listActivityProviders();
        // NOTE: an empty list will also pass this test.
        $this->assertContainsOnlyInstancesOf(ScormCloud\LRSAccount::class, $list);
    }

    public function testCreateActivityProvider()
    {
        $service = new ScormEngineService('4R7SARI28E', '41MMLXlRxLYv2OikL4sSMeW3zkA0BjlabnDwbiaL', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $provider = $service->lrsAccountService()->createActivityProvider();
        return $provider;
    }


    /**
     * @depends testCreateActivityProvider
     */
    public function testEditActivityProvider($provider)
    {
        $service = new ScormEngineService('4R7SARI28E', '41MMLXlRxLYv2OikL4sSMeW3zkA0BjlabnDwbiaL', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $newProvider = $service->lrsAccountService()->editActivityProvider($provider->getKey(), null, null, "Edited Provider", '4R7SARI28E');
        $this->assertEquals("Edited Provider", $newProvider->getLabel());
        $this->assertEquals('4R7SARI28E', $newProvider->getAllowedEndpoints());
        return $newProvider;
    }

    /**
     * @depends testEditActivityProvider
     */
    public function testDeleteActivityProvider($provider)
    {
        $service = new ScormEngineService('4R7SARI28E', '41MMLXlRxLYv2OikL4sSMeW3zkA0BjlabnDwbiaL', 'Test Company', 'Initial Application for Realm', '1.0.0');
        $response = $service->lrsAccountService()->deleteActivityProvider($provider->getKey());
        //NOTE: If the deletion fails, expect an exception.
        $this->assertTrue($response);
    }
}
