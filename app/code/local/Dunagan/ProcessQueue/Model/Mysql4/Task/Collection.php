<?php
/**
 * Author: Sean Dunagan
 * Created: 8/13/15
 * Class Dunagan_ProcessQueue_Model_Mysql_Task_Collection
 */

class Dunagan_ProcessQueue_Model_Mysql4_Task_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_default_minutes_in_past_threshold = 120;

    protected function _construct()
    {
        $this->_init('dunagan_process_queue/task');
    }

    public function addOpenForProcessingFilter()
    {
        $pending_status = Dunagan_ProcessQueue_Model_Task::STATUS_PENDING;
        $error_status = Dunagan_ProcessQueue_Model_Task::STATUS_ERROR;
        $open_for_processing_states = array($pending_status, $error_status);

        $this->addFieldToFilter('status', array('in' => $open_for_processing_states));
        return $this;
    }

    public function addLastExecutedAtThreshold($minutes_in_past = null)
    {
        if (is_null($minutes_in_past))
        {
            $minutes_in_past = $this->_default_minutes_in_past_threshold;
        }
        $current_gmt_timestamp = Mage::getSingleton('core/date')->gmtTimestamp();
        $second_in_past_timestamp = $minutes_in_past * 60;
        $last_executed_at_threshold_timestamp = $current_gmt_timestamp - $second_in_past_timestamp;
        $last_executed_at_threshold_datetime = date('Y-m-d H:i:s', $last_executed_at_threshold_timestamp);

        $this->addFieldToFilter('last_executed_at', array('lt' => $last_executed_at_threshold_datetime));
        return $this;
    }

    public function addCustomCodesFilter($custom_codes_filter_array)
    {
        $this->addFieldToFilter('code', $custom_codes_filter_array);
        return $this;
    }

    public function addCodeFilter($code)
    {
        if (!is_array($code))
        {
            $this->addFieldToFilter('code', $code);
        }
        else
        {
            $this->addFieldToFilter('code', array('in' => $code));
        }

        return $this;
    }

    /**
     * Adds a filter of codes to omit from the collection
     *
     * @param array|string $codes_to_omit
     * @return $this
     */
    public function addCodesToOmitFilter($codes_to_omit)
    {
        if (!is_array($codes_to_omit))
        {
            $codes_to_omit = array($codes_to_omit);
        }

        $this->addFieldToFilter('code', array('nin' => $codes_to_omit));
        return $this;
    }

    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    public function sortByLeastRecentlyExecuted()
    {
        $this->getSelect()->order('last_executed_at ' . Zend_Db_Select::SQL_ASC);
        return $this;
    }
}
