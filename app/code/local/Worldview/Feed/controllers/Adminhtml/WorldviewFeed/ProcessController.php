<?php

/**
 * Author: Sean Dunagan
 * Created: 4/11/15
 *
 * Class Worldview_Feed_ProcessController
 */

class Worldview_Feed_Adminhtml_WorldviewFeed_ProcessController extends Mage_Adminhtml_Controller_Action
{
    const EXCEPTION_EXECUTING_PROCESS = 'An uncaught exception occurred when attempting to process article retrieval in method %s::%s : %s';

    // TODO implement acl for this controller
    public function articleRetrievalAction()
    {
        try
        {
            $articleRetrievalTaskObject = Mage::helper('worldview_feed/article_retrieval_processor')
                                            ->createQueueTaskInProcessingState();

            $taskExecutionResult = Mage::helper('dunagan_process_queue/task_processor')
                                        ->processQueueTask($articleRetrievalTaskObject, true);

            $success_message = $taskExecutionResult->getTaskStatusMessage();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__($success_message));
        }
        catch(Exception $e)
        {
            $error_message = sprintf(self::EXCEPTION_EXECUTING_PROCESS, __CLASS__, __METHOD__, $e->getMessage());
            Mage::log($error_message);
            $exceptionToLog = new Exception($error_message);
            Mage::logException($exceptionToLog);
            Mage::getSingleton('adminhtml/session')->addError($this->__($error_message));
        }

        // Redirect to the articles grid page
        return $this->_redirect('adminhtml/WorldviewFeed_index/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('worldview/feeds/process');
    }
}
