<?php
/*
 * @author Sean Dunagan (sean.dunagan@healthsqyre.com)
 */

/**
 * Class Dunagan_WebApi_Model_Result
 */
class Dunagan_WebApi_Model_Result extends Dunagan_Base_Model_Result implements Dunagan_WebApi_Model_ResultInterface
{
    /**
     * @var null|string
     */
    protected $_api_method = null;

    /**
     * @var null|array|string
     */
    protected $_api_method_parameters = null;

    /**
     * @var null|string
     */
    protected $_status_code = null;

    /**
     * {@inheritdoc}
     */
    public function getApiMethod()
    {
        return $this->_api_method;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiMethod($api_method)
    {
        $this->_api_method = $api_method;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiMethodParameters()
    {
        return $this->_api_method_parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiMethodParameters($api_method_parameters)
    {
        $this->_api_method_parameters = $api_method_parameters;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->_status_code;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusCode($status_code)
    {
        $this->_status_code = $status_code;
        return $this;
    }
}