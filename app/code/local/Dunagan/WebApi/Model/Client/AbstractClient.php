<?php
/*
 * @author Sean Dunagan (sean.dunagan@healthsqyre.com)
 */

/**
 * Class AbstractClient
 */
abstract class Dunagan_WebApi_Model_Client_AbstractClient
{
    const EXCEPTION_EXECUTING_SOAP_API_CALL = "An exception occurred while executing API call %s with API client model %s and parameters %s:\nDetail: %s\nError Message: %s";
    const EXCEPTION_EXECUTING_API_CALL = "An exception occurred while executing API call %s with API client model %s: %s\n\nThe request parameters were: %s";
    const DEBUG_TIME_TO_EXECUTE_MESSAGE = 'Method %s took %s seconds to execute';
    const DEBUG_API_CALL_RESPONSE = "Method: %s was successfully called\nParameters: %s\nResponse: %s";
    const DEBUG_API_EXCEPTION_RESPONSE = "%s\n%s";

    /**
     * @var string
     */
    protected $_debug_log_filename = 'dunagan_web_api_debug.log';

    /**
     * Allows subclasses to prevent request response logging if the subclass already intends on logging
     *
     * @var bool
     */
    protected $_debug_request_response = true;

    /**
     * Executes the API call
     *
     * @param string $method
     * @param array $parameters
     * @param Dunagan_WebApi_Model_ResultInterface $apiCallResultObject
     * @return mixed
     */
    abstract public function executeApiCall($method, $parameters, $apiCallResultObject);

    /**
     * Method to implement a call to the Web API. It will catch any exceptions which are thrown by the attempted
     *  execution and log the exception
     *
     * @param string $method
     * @param array $parameters
     * @return Dunagan_WebApi_Model_ResultInterface
     */
    public function callApiMethod($method, $parameters)
    {
        $apiCallResultObject = Mage::getModel('dunagan_web_api/result');
        /* @var Dunagan_WebApi_Model_ResultInterface $apiCallResultObject */
        $apiCallResultObject->setApiMethod($method);
        $apiCallResultObject->setApiMethodParameters($parameters);

        $is_debug_mode = $this->_isDebugModeEnabled();

        try
        {
            if ($is_debug_mode)
            {
                $before_call_timestamp = microtime(true);
            }

            $apiCallResultObject = $this->executeApiCall($method, $parameters, $apiCallResultObject);

            if ($is_debug_mode)
            {
                $after_call_timestamp = microtime(true);
                $time_to_execute_in_ms = $after_call_timestamp - $before_call_timestamp;
                $time_to_execute_in_sec = $time_to_execute_in_ms / 1000.0;
                $time_to_execute_message = sprintf(self::DEBUG_TIME_TO_EXECUTE_MESSAGE, $method, $time_to_execute_in_sec);
                Mage::log($time_to_execute_message, null, $this->_debug_log_filename);

                if ($this->_debug_request_response)
                {
                    $response = $apiCallResultObject->getResultMessage();
                    $api_log_message = sprintf(self::DEBUG_API_CALL_RESPONSE, $method, print_r($parameters, true), print_r($response, true));
                    Mage::log($api_log_message, null, $this->_debug_log_filename);
                }
            }
        }
        catch(SoapFault $e)
        {
            // In the event that this Client is a SOAP Client
            $detail = isset($e->detail) ? $e->detail : 'No details were provided regarding this error';
            $error_message = sprintf(self::EXCEPTION_EXECUTING_SOAP_API_CALL, $method, get_class($this),
                                     print_r($parameters, true), $detail, $e->__toString());
            $apiCallResultObject->setWasSuccessful(false);
            $apiCallResultObject->setResultMessage($error_message);
        }
        catch(Exception $e)
        {
            $error_message = sprintf(self::EXCEPTION_EXECUTING_API_CALL, $method, get_class($this),
                                     $e->getMessage(), print_r($parameters, true));
            $apiCallResultObject->setWasSuccessful(false);
            $apiCallResultObject->setResultMessage($error_message);
        }

        if ((!$apiCallResultObject->getWasSuccessful()) && $is_debug_mode)
        {
            $after_call_timestamp = microtime(true);
            $time_to_execute_in_sec = $after_call_timestamp - $before_call_timestamp;
            $time_to_execute_message = sprintf(self::DEBUG_TIME_TO_EXECUTE_MESSAGE, $method, $time_to_execute_in_sec);

            $api_log_message = sprintf(self::DEBUG_API_EXCEPTION_RESPONSE, print_r($apiCallResultObject->getResultMessage(), true),
                                       $time_to_execute_message);
            Mage::log($api_log_message, null, $this->_debug_log_filename);
        }

        return $apiCallResultObject;
    }

    /**
     * This method is built out to allow subclasses to extend this method. It is expected to be overridden
     *
     * @return mixed
     */
    protected function _isDebugModeEnabled()
    {
        // TODO Create system configuration setting for this if this class is going to be used
        //return $this->_scopeConfig->getValue($this->_is_debug_mode_enabled_configuration_path);

        return true;
    }
}
