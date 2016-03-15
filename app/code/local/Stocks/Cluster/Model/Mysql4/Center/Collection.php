<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Model_Mysql4_Center_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('stocks_cluster/center');
    }

    public function getSize()
    {
        if (is_null($this->_totalRecords)) {
            $sql = $this->getSelectCountSql();
            $records = $this->getConnection()->fetchAll($sql, $this->_bindParams);
            $this->_totalRecords = count($records);
        }
        return intval($this->_totalRecords);
    }
}
