<?php
/**
 * Author: Sean Dunagan
 * Created: 12/12/15
 * Interface Dunagan_Base_Model_Webservice_Request_Response_Interface
 */
interface Dunagan_Base_Model_Webservice_Request_Response_Interface
{
    /**
     * Returns whether the Request was successful
     *
     * @return bool
     */
    public function wasSuccessful();

    /**
     * Mutator method to set if the request was successful
     *
     * @param bool $was_successful
     * @return $this
     */
    public function setWasSuccessful($was_successful);

    /**
     * @return null|string
     */
    public function getErrorMessage();

    /**
     * Mutator method to error message
     *
     * @param string $error_message
     * @return $this
     */
    public function setErrorMessage($error_message);

    /**
     * Returns the success message
     *
     * @return null|string
     */
    public function getSuccessMessage();

    /**
     * Mutator method to set the success message
     *
     * @param string $success_message
     * @return $this
     */
    public function setSuccessMessage($success_message);
}
