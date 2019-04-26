<?php
/*
 * @author Sean Dunagan (sean.dunagan@healthsqyre.com)
 */

/**
 * Interface Dunagan_WebApi_Model_ResultInterface
 */
interface Dunagan_WebApi_Model_ResultInterface extends Dunagan_Base_Model_ResultInterface
{
    /**
     * Accessor method for the $_api_method instance field
     *
     * @return null|string
     */
    public function getApiMethod();

    /**
     * Mutator method for the $_api_method instance field
     *
     * @param string $api_method
     * @return $this
     */
    public function setApiMethod($api_method);

    /**
     * Accessor method for the $_api_method_parameters instance field
     *
     * @return null|array
     */
    public function getApiMethodParameters();

    /**
     * Mutator method for the $_api_method_parameters instance field
     *
     * @param array $api_method_parameters
     * @return $this
     */
    public function setApiMethodParameters($api_method_parameters);

    /**
     * Accessor method for the $_status_code instance field
     *
     * @return string
     */
    public function getStatusCode();

    /**
     * Mutator method for the $_status_code instance field
     *
     * @param string $status_code
     * @return $this
     */
    public function setStatusCode($status_code);
}
