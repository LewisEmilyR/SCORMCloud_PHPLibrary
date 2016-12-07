<?php

namespace ScormCloud;

/**
 * These objects are described as "Activity Providers" on the scorm cloud LRSView page.
 */
class LrsAccount
{
    protected $label;
    protected $key;
    protected $secret;
    protected $enabled;
    protected $authType;
    protected $allowedEndpoints;
    protected $permissionsLevel;

    /**
     * Creates new LrsAccount object from XML returned from the Scorm Cloud API.
     * @param  string $xml
     * @return LrsAccount
     */
    public static function fromXML($xml)
    {
        //Note: xml id property is identical to accountKey
        $label = (string) $xml->accountLabel;
        $key = (string) $xml->accountKey;
        $secret = (string) $xml->accountSecret;
        $authType = (string) $xml->accountAuthType;
        $enabled = (boolean) $xml->accountEnabled;
        $allowedEndpoints = (string) $xml->allowedEndpoints;
        $permissionsLevel = (string) $xml->permissionsLevel;
        if (empty($key) || empty($secret) || empty($label) || empty($enabled)) {
            throw new Exception("Invalid LrsAccount XML");
        }
        return new self($label, $key, $secret, $authType, $enabled, $allowedEndpoints, $permissionsLevel);
    }

    public function __construct($label, $key, $secret, $authType, $enabled, $allowedEndpoints, $permissionsLevel)
    {
        $this->label = $label;
        $this->key = $key;
        $this->secret = $secret;
        $this->enabled = $enabled;
        $this->authType = $authType;
        $this->allowedEndpoints = $allowedEndpoints;
        $this->permissionsLevel = $permissionsLevel;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getAuthType()
    {
        return $this->authType;
    }

    public function getPermissionsLevel()
    {
        return $this->permissionsLevel;
    }

    public function getAllowedEndpoints()
    {
        return $this->allowedEndpoints;
    }
}
