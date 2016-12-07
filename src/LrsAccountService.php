<?php

namespace ScormCloud;

use Exception;

class LrsAccountService
{
    protected $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * Creates a new default activity provider with the name "Unnamed Account", basic authentication, and read/write permissions to ALL endpoints.
     * Combine with editActivityProvider to actually have some control over the resulting object.
     * @return LRSAccount
     */
    public function createActivityProvider()
    {
        $response = $this->serviceRequest->callService("rustici.lrsaccount.createActivityProvider");
        $lrsAccount = LrsAccount::fromXML($response->activityProvider);
        return $lrsAccount;
    }

    /**
     * Returns the array of all activity providers, not just the ones associated with a given appId.
     * @return array
     */
    public function listActivityProviders()
    {
        $response = $this->serviceRequest->callService("rustici.lrsaccount.listActivityProviders");
        $lrsAccounts = [];
        foreach ($response->activityProviderList->activityProvider as $activityProvider) {
            $lrsAccounts[] = LrsAccount::fromXML($activityProvider);
        }

        return $lrsAccounts;
    }

    /**
     * Allows setting various parts of the indicated activity provider.
     * @param  string $accountKey       key indicates which activity provider to edit.
     * @param  string $isActive         true or false
     * @param  string $authType         'basic' or 'oauth'
     * @param  string $label            name of activity provider
     * @param  string $appId            appId for associated endpoint. Add '_sandbox' to point the activity provider to the endpoint's sandbox.
     * @param  string $permissionslevel 'DEFAULT', 'READ_ONLY', 'READ_ANY' or 'WRITE_ONLY'
     * @return LRSAccount
     */
    public function editActivityProvider($accountKey, $isActive = null, $authType = null, $label = null, $appId = null, $permissionslevel = null)
    {
        $params = ['accountkey' => $accountKey];

        if (isset($isActive)) {
            $params['isactive'] = $isActive;
        }
        if (isset($authType)) {
            $params['authtype'] = $authType;
        }
        if (isset($label)) {
            $params['label'] = $label;
        }
        if (isset($appId)) {
            $params['allowedendpoints'] = $appId;
        }
        if (isset($permissionslevel)) {
            $params['permissionslevel'] = $permissionslevel;
        }
        $this->serviceRequest->setMethodParams($params);
        $response = $this->serviceRequest->callService("rustici.lrsaccount.editActivityProvider");
        $lrsAccount = LrsAccount::fromXML($response->activityProvider);
        return $lrsAccount;
    }

    /**
     * removes the indicated activity provider
     * @param  string $accountKey
     * @return boolean
     */
    public function deleteActivityProvider($accountKey)
    {
        $params = ['accountkey' => $accountKey];
        $this->serviceRequest->setMethodParams($params);
        $response = $this->serviceRequest->callService("rustici.lrsaccount.deleteActivityProvider");
        return true;
    }

    /**
     * Set App Lrs Auth Callback URL.
     * @param string $callbackUrl
     * @return boolean
     */
    public function setAppLrsAuthCallbackUrl($callbackUrl)
    {
        $params = ['lrsAuthCallbackUrl' => $callbackUrl];
        $this->serviceRequest->setMethodParams($params);
        $response = $this->serviceRequest->callService("rustici.lrsaccount.setAppLrsAuthCallbackUrl");
        return true;
    }
}
