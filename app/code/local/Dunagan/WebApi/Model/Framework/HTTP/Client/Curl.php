<?php
/*
 * @author Sean Dunagan (sean.dunagan@healthsqyre.com)
 */

/**
 * Class Dunagan_WebApi_Model_Framework_HTTP_Client_Curl
 */
class Dunagan_WebApi_Model_Framework_HTTP_Client_Curl extends Mage_HTTP_Client_Curl
{
    /**
     * Make request
     * This method is publicly scoped to allow for making PUT/DELETE calls
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function makeMethodRequest($method, $uri, $params = [])
    {
        $this->makeRequest($method, $uri, $params);
    }
}
