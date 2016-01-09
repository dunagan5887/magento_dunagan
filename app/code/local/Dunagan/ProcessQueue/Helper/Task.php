<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 12/9/15
 */

class Dunagan_ProcessQueue_Helper_Task extends Mage_Core_Helper_Data
{
    const STALE_TIME_LAPSE = '-2 weeks';

    const TASK_CODES_TO_OMIT_FROM_CRONTAB_CONFIG_PATH = 'dunagan_process_queue/crontab_processor/task_codes_to_omit';

    /**
     * Returns an array of task codes which should be not be processed via Magento crontab execution
     *
     * @return array
     */
    public function getTaskCodesToOmitFromCrontabProcessing()
    {
        $task_codes_to_omit = Mage::getStoreConfig(self::TASK_CODES_TO_OMIT_FROM_CRONTAB_CONFIG_PATH);
        if (!is_array($task_codes_to_omit))
        {
            // There must not be any task codes flagged to be omitted
            $task_codes_to_omit = array();
        }
        else
        {
            // $task_codes_to_omit will be an array of job_code => ""
            $task_codes_to_omit = array_keys($task_codes_to_omit);
        }
        return $task_codes_to_omit;
    }

    /**
     * The calling block is expected to catch exceptions
     *
     * @param null|string $task_code
     * @return int - Number of rows deleted
     */
    public function deleteStaleSuccessfulTasks($task_code = null)
    {
        $current_gmt_timestamp = Mage::getSingleton('core/date')->gmtTimestamp();
        $stale_timestamp = strtotime(self::STALE_TIME_LAPSE, $current_gmt_timestamp);
        $stale_date = date('Y-m-d H:i:s', $stale_timestamp);

        $rows_deleted = Mage::getResourceSingleton('dunagan_process_queue/task')
                            ->deleteSuccessfulTasks($task_code, $stale_date);
        $unique_rows_deleted = Mage::getResourceSingleton('dunagan_process_queue/task_unique')
                                ->deleteSuccessfulTasks($task_code, $stale_date);

        return ($rows_deleted + $unique_rows_deleted);
    }
}
