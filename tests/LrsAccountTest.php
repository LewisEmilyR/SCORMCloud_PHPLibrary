<?php

use PHPUnit\Framework\TestCase;
use ScormCloud\LrsAccount;

class LrsAccountTest extends TestCase
{
    public function testFromXML()
    {
        $xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8" ?>
        <rsp stat="ok">
                <activityProvider>
                    <id><![CDATA[A Id]]></id>
                    <accountLabel><![CDATA[A Label]]></accountLabel>
                    <accountKey><![CDATA[A Key]]></accountKey>
                    <accountSecret><![CDATA[A Secret]]></accountSecret>
                    <accountAuthType><![CDATA[basic]]></accountAuthType>
                    <accountEnabled>true</accountEnabled>
                    <allowedEndpoints><![CDATA[An App Id]]></allowedEndpoints>
                    <permissionsLevel><![CDATA[READ_ONLY]]></permissionsLevel>
                </activityProvider>
        </rsp>');
        $test = LrsAccount::fromXML($xml->activityProvider);
        $this->assertEquals('A Key', $test->getKey());
        $this->assertEquals('A Secret', $test->getSecret());
        $this->assertEquals('A Label', $test->getLabel());
        $this->assertEquals(true, $test->getEnabled());
        $this->assertEquals('basic', $test->getAuthType());
        $this->assertEquals('READ_ONLY', $test->getPermissionsLevel());
        $this->assertEquals('An App Id', $test->getAllowedEndpoints());
    }
}