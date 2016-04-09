<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/8/16
 */

class Stocks_Down_Model_Mysql4_Stock_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('stocks_down/stock');
    }
}
