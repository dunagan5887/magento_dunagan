<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 12/9/15
 */

class Dunagan_ProcessQueue_Model_Cron_Delete_Stale_Successful
{
    const CRON_UNCAUGHT_EXCEPTION = 'Error deleting stale success tasks from the Process Queue: %s';

    public function deleteStaleSuccessfulQueueTasks()
    {
        try
        {
            Mage::helper('dunagan_process_queue/task')->deleteStaleSuccessfulTasks();
            Mage::helper('reverb_process_queue/unique_task')->deleteStaleSuccessfulTasks();
        }
        catch(Exception $e)
        {
            $error_message = sprintf(self::CRON_UNCAUGHT_EXCEPTION, $e->getMessage());
            Mage::getSingleton('dunagan_process_queue/log')->logQueueProcessorError($error_message);
        }
    }
}
