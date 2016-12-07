<?php

/**
 * TODO: add back curl proxy feature.
 */

namespace ScormCloud;

use Exception;

class ServiceRequest
{
    // Number of seconds to wait while connecting to the server.
    const TIMEOUT_CONNECTION = 30;
    // Total number of seconds to wait for a request.
    const TIMEOUT_TOTAL = 500;
    // Where to access web engine services
    const DEFAULT_SERVICE_URL = 'https://cloud.scorm.com/EngineWebServices';
    protected $appId;
    protected $securityKey;
    protected $originString;
    protected $serviceUrl;
    protected $additionalParams = [];

    public function __construct($appId, $securityKey, $originString, $serviceUrl = null)
    {
        $this->appId = $appId;
        $this->securityKey = $securityKey;
        $this->originString = $originString;
        if (isset($serviceUrl)) {
            $this->serviceUrl = $serviceUrl;
        } else {
            $this->serviceUrl = self::DEFAULT_SERVICE_URL;
        }
    }

    /**
     * Add additional parameters to the final url
     * @param array $params
     */
    public function setMethodParams(array $params)
    {
        $this->additionalParams = array_merge($this->additionalParams, $params);
    }

    /**
     * construct and submit a post request to the scorm cloud api
     * @param  string $methodName api action
     * @param  string $file       optional filename
     * @return simpleXMLobject
     */
    public function callService($methodName, $file = null)
    {
        if (!empty($file)) {
            $postParams = $this->handleFile($file);
        } else {
            $postParams = "";
        }

        $url = $this->constructUrl($methodName, $this->serviceUrl);
        $responseText = $this->submitPost($url, $postParams);
        $response = $this->assertNoErrorAndReturnXML($responseText);

        return $response;
    }

    /**
     * formats file data to send over curl
     * @param  string $file filename
     * @return array
     */
    protected function handleFile($file)
    {
        return array('filedata' => curl_file_create($file));
    }

    /**
     * creates the url where all scorm cloud data will be posted
     * @param  string $methodName api action
     * @param  string $serviceUrl root url
     * @return string
     */
    protected function constructUrl($methodName, $serviceUrl)
    {
        $parameterMap = [
            'method' => $methodName,
            'appid' => $this->appId,
            'origin' => $this->originString,
            'ts' => gmdate("YmdHis")
        ];

        $params = array_merge($parameterMap, $this->additionalParams);
        $url = $serviceUrl.'/api?'.$this->signParams($this->securityKey, $params);
        return $url;
    }

    /**
     * formats url parameters and adds the security key signature composed of those parameters
     * @param  string $securityKey
     * @param  array $parameters
     * @return string
     */
    protected function signParams($securityKey, $parameters)
    {
        ksort($parameters, SORT_STRING | SORT_FLAG_CASE);
        $signing = '';
        foreach ($parameters as $key => $value) {
            $signing .= $key.$value;
        }
        $parameters['sig'] = md5($securityKey.$signing);

        return http_build_query($parameters);
    }

    /**
     * executes a curl request to the given url
     * @param  string $url        with parameters
     * @param  array $postParams  parameters submitted separately from the url
     * @return string
     */
    protected function submitPost($url, $postParams)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // make sure we submit this as a post
        curl_setopt($ch, CURLOPT_POST, true);
        if (isset($postParams)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);

        // make sure problems are caught
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);

        // return the output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // set the timeouts
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT_CONNECTION);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT_TOTAL);

        //set header expect empty for upload issue...
        //TODO: find out what upload issue previous comments were talking about.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        // set the PHP script's timeout to be greater than CURL's
        set_time_limit(self::TIMEOUT_CONNECTION + self::TIMEOUT_TOTAL + 5);

        $result = curl_exec($ch);
        if (0 !== curl_errno($ch)) {
            throw new Exception("POST Request failed. Error: ".curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

    /**
     * converts given xml result string to xml object, checking for parsing errors and formatted scorm cloud errrors
     * @param  string $xmlString
     * @return simpleXMLobject
     */
    protected function assertNoErrorAndReturnXML($xmlString)
    {
        $xmlDoc = simplexml_load_string($xmlString);

        if ($xmlDoc === false) {
            throw new Exception("XML Parsing error: ".implode(libxml_get_errors(",\n"), ''));
        }

        if ($xmlDoc["stat"] == 'fail') {
            throw new Exception("SCORM Cloud Error: ".$xmlDoc->err["code"]." - ".$xmlDoc->err["msg"]);
        } elseif ($xmlDoc["stat"] != 'ok') {
            throw new Exception("Invalid XML Response from web service call, expected <rsp> tag, instead recieved: ".$xmlString);
        }

        return $xmlDoc;
    }
}
