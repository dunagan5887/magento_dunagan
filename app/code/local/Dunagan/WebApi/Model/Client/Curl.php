<?php
/*
 * @author Sean Dunagan (sean.dunagan@healthsqyre.com)
 */

/**
 * Class Dunagan_WebApi_Model_Client_Curl
 */
class Dunagan_WebApi_Model_Client_Curl extends Dunagan_WebApi_Model_Client_AbstractClient
{
    const ERROR_STATUS_NOT_OK = 'Request returned a status of %s. Reponse was: %s';
    const ERROR_NO_URL_DEFINED = 'No url was defined for a %s CURL request';

    /**
     * Sublasses can redefine this array as needed to add headers
     *
     * @var array
     */
    protected $_headers_to_add = array();

    /**
     * @param string $method
     * @param mixed $parameters
     * @param Dunagan_WebApi_Model_ResultInterface $apiCallResultObject
     *
     * @return Dunagan_WebApi_Model_ResultInterface
     * @throws Exception
     */
    public function executeApiCall($method, $parameters, $apiCallResultObject)
    {
        $curlClient = Mage::getModel('dunagan_web_api/Framework_HTTP_Client_Curl');
        /* @var Dunagan_WebApi_Model_Framework_HTTP_Client_Curl $curlClient */

        $this->_addHeaders($curlClient);

        $url_to_call = isset($parameters['url']) ? $parameters['url'] : null;
        if (empty($url_to_call))
        {
            $error_message = sprintf(self::ERROR_NO_URL_DEFINED, $method);
            throw new Exception($error_message);
        }
        $curl_request_parameters = isset($parameters['curl_params']) ? $parameters['curl_params'] : [];

        $curlClient->makeMethodRequest($method, $url_to_call, $curl_request_parameters);

        $status = $curlClient->getStatus();
        $response = $curlClient->getBody();

        $apiCallResultObject->setResultMessage($response);
        $apiCallResultObject->setStatusCode($status);

        $status_as_int = intval($status);
        if (($status_as_int >= 200) && ($status_as_int <= 299))
        {
            $apiCallResultObject->setWasSuccessful(true);
        }
        else
        {
            $error_message = sprintf(self::ERROR_STATUS_NOT_OK, $status, $response);
            $apiCallResultObject->setWasSuccessful(false);
            throw new Exception($error_message);
        }

        return $apiCallResultObject;
    }

    /**
     * @param Mage_HTTP_Client_Curl $curlClient
     */
    protected function _addHeaders($curlClient)
    {
        foreach($this->_headers_to_add as $header_name => $header_value)
        {
            $curlClient->addHeader($header_name, $header_value);
        }
    }
}
