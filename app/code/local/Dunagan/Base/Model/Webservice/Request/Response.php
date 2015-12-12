<?php
/**
 * Author: Sean Dunagan
 * Created: 12/12/15
 * Class Dunagan_Base_Model_Webservice_Request_Response
 */
class Dunagan_Base_Model_Webservice_Request_Response
    implements Dunagan_Base_Model_Webservice_Request_Response_Interface
{
    protected $_was_successful = null;
    protected $_error_message = null;
    protected $_success_message = null;

    /**
     * Returns whether the Request was successful
     *
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->_was_successful;
    }

    /**
     * Mutator method to set if the request was successful
     *
     * @param bool $was_successful
     * @return $this
     */
    public function setWasSuccessful($was_successful)
    {
        $this->_was_successful = $was_successful;
        return $this;
    }

    /**
     * Returns the success message
     *
     * @return null|string
     */
    public function getSuccessMessage()
    {
        return $this->_success_message;
    }

    /**
     * Mutator method to set the success message
     *
     * @param string $success_message
     * @return $this
     */
    public function setSuccessMessage($success_message)
    {
        $this->_success_message = $success_message;
        return $this;
    }

    /**
     * Returns the error message
     *
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->_error_message;
    }

    /**
     * Mutator method to set the error message
     *
     * @param string $error_message
     * @return $this
     */
    public function setErrorMessage($error_message)
    {
        $this->_error_message = $error_message;
        return $this;
    }
}
