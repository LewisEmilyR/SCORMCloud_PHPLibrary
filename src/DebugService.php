<?php

namespace ScormCloud;

class DebugService
{
    protected $serviceRequest;

    public function __construct(serviceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * check for a valid response from the scorm web engine service url.
     * @return boolean
     */
    public function cloudPing()
    {
        $response = $this->serviceRequest->callService("rustici.debug.ping");
        return ($response["stat"] == 'ok');
    }

    /**
     * check for a valid and authorized response from the scorm web engine.
     * Will work for LRS endpoints, will not work for the app management key and secret.
     * @return boolean
     */
    public function cloudAuthPing()
    {
        $response = $this->serviceRequest->callService("rustici.debug.authPing");
        return ($response["stat"] == 'ok');
    }
}
