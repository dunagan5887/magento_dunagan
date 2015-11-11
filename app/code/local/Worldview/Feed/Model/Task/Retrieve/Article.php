<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 11/10/15
 */

class Worldview_Feed_Model_Task_Retrieve_Article extends Dunagan_ProcessQueue_Model_Task
{
    const TASK_CODE = 'worldview_retrieve_articles';
    const TASK_OBJECT = 'worldview_feed/task_retrieve_article';
    const TASK_METHOD = 'retrieveArticlesFromSources';

    public function retrieveArticlesFromSources($argumentsObject)
    {
        // Need to make modifications to be able to get log data in html, not text
        $helper = Mage::helper('worldview_feed/article_retrieval_processor');
        $process_log_data_objects_array = $helper->executeProcesses();

        $log_html_string = '';

        foreach($process_log_data_objects_array as $processLogData)
        {
            $log_html_string .= $processLogData->getCurrentLogData();
        }

        return $this->_returnSuccessCallbackResult($log_html_string);
    }
}
