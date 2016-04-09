<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/8/16
 */

class Stocks_Down_Model_Mysql4_Stock extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('stocks_down/stocks_down','symbol');
    }
}
