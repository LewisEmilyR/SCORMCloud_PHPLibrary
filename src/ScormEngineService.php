<?php

namespace ScormCloud;

use Exception;

/**
* The main hub for all Scorm Cloud api calls.
*/
class ScormEngineService
{
    protected $originString;
    protected $appId;
    protected $secretKey;
    protected $serviceRequest;

    public function __construct($appId, $secretKey, $companyName, $appName, $scormCloudVersion, $serviceUrl = null)
    {
        $this->appId = $appId;
        $this->secretKey = $secretKey;
        $this->originString = $this->createOriginString($companyName, $appName, $scormCloudVersion);
        $this->serviceRequest = new ServiceRequest($this->appId, $this->secretKey, $this->originString, $serviceUrl);
    }

    /**
     * As defined in the Scorm Cloud documentation at http://cloud.scorm.com/doc/web-services/api.html
     * "The origin string parameter must not contain spaces or any other special URL characters and should be in the format of: [company/organization].[application name].[application version].
     * All characters in the organization and application names should be lowercase with spaces removed. Versions can contain periods and dashes."
     * @param  string $companyName
     * @param  string $appName
     * @param  string $scormCloudVersion
     * @return string
     */
    protected function createOriginString($companyName, $appName, $scormCloudVersion)
    {
        $companyComponent = preg_replace('/[^a-z0-9]/', '', strtolower($companyName));
        $applicationComponent = preg_replace('/[^a-z0-9]/', '', strtolower($appName));
        $versionComponent = preg_replace('/[^\\w\\.\\-]/', '', strtolower($scormCloudVersion));
        
        return "$companyComponent.$applicationComponent.$versionComponent";
    }

    /**
     * Changes origin string for the service.
     * @param  string $companyName
     * @param  string $appName
     * @param  string $scormCloudVersion
     * @return string
     */
    public function setOriginString($companyName, $appName, $scormCloudVersion)
    {
        $this->originString = $this->createOriginString($companyName, $appName, $scormCloudVersion);
        return $this->originString;
    }

    /**
     * View current service origin string.
     * @return string
     */
    public function getOriginString()
    {
        return $this->originString;
    }

    /**
     * Verifies management credentials for the service.
     * @param  string $appId
     * @param  string $secretKey
     * @return boolean
     */
    public function isValidAccount()
    {
        array_filter(
            get_object_vars($this),
            function ($property, $value) {
                if (empty($value)) {
                    throw new Exception("Empty required property [{$property}], could not connect to Web Engine Service.");
                }
            },
            ARRAY_FILTER_USE_BOTH
        );
        return $this->debugService()->cloudAuthPing();
    }

    /**
     * Verifies the web engine service url
     * @return boolean
     */
    public function isValidUrl()
    {
        return $this->debugService()->cloudPing();
    }

    public function courseService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function registrationService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function uploadService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function reportingService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function taggingService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function accountService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function debugService()
    {
        return new DebugService($this->serviceRequest);
    }

    public function dispatchService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function invitationService()
    {
        throw new Exception("Service not yet enabled.");
    }

    public function lrsAccountService()
    {
        return new lrsAccountService($this->serviceRequest);
    }

    public function applicationService()
    {
        throw new Exception("Service not yet enabled.");
    }
}
