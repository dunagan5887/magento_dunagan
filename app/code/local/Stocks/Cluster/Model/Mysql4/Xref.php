<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Model_Mysql4_Xref extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('stocks_cluster/symbol_xref', 'symbol');
    }
}
