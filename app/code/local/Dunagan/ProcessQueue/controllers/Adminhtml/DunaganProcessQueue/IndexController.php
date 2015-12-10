<?php
/**
 * Author: Sean Dunagan
 * Created: 9/14/15
 */

class Dunagan_ProcessQueue_Adminhtml_DunaganProcessQueue_IndexController
    extends Dunagan_Base_Controller_Adminhtml_Form_Abstract
    implements Dunagan_Base_Controller_Adminhtml_Form_Interface
{
    const ERROR_CLEARING_ALL_TASKS = 'An error occurred while clearing all tasks with job code %s: %s';
    const ERROR_CLEARING_SUCCESSFUL_TASKS = 'An error occurred while clearing all successful tasks with job code %s: %s';
    const SUCCESS_CLEARED_ALL_TASKS_WITH_CODE = 'Successfully cleared all tasks with code %s';
    const SUCCESS_CLEARED_SUCCESSFUL_TASKS_WITH_CODE = 'Successfully cleared all successful tasks with code %s';
    const SUCCESS_CLEARED_ALL_TASKS = 'Successfully cleared all tasks';
    const SUCCESS_CLEARED_SUCCESSFUL_TASKS = 'Successfully cleared all successful tasks';
    const EXCEPTION_LOAD_TASK = 'An exception occurred while attempting to load the queued task with id %s to manually act on the task: %s';
    const EXCEPTION_ACT_ON_TASK = 'An error occurred while acting on the task with id %s: %s';
    const GENERIC_ADMIN_FACING_ERROR_MESSAGE = 'An error occurred with your request. Please try again.';
    const ERROR_UPDATE_STATUS_UNALLOWED = 'You do not have authorization to update the status for this task';
    const NOTICE_TASK_ACTION = 'The attempt to %s the process queue task with id %s has completed.';
    const NOTICE_EXPEDITE_COMPLETED = 'The attempt to expedite tasks has completed';
    const EXCEPTION_EXPEDITING = 'An exception occurred while expediting tasks: %s';

    protected $_adminHelper = null;

    public function expediteAction()
    {
        try
        {
            $codes_to_filter_by = $this->getCodesToFilterBy();

            Mage::helper('dunagan_process_queue/task_processor')->processQueueTasks($codes_to_filter_by, true);
            Mage::helper('dunagan_process_queue/task_processor_unique')->processQueueTasks($codes_to_filter_by, true);
        }
        catch(Exception $e)
        {
            $error_message = $this->__(self::EXCEPTION_EXPEDITING, $e->getMessage());
            Mage::log($error_message);
            Mage::getSingleton('adminhtml/session')->addError($error_message);
            $exception = new Dunagan_Base_Controller_Varien_Exception($error_message);
            $exception->prepareRedirect('*/*/index');
            throw $exception;
        }

        $notice_message = $this->__(self::NOTICE_EXPEDITE_COMPLETED);
        Mage::getSingleton('adminhtml/session')->addNotice($this->__($notice_message));
        $this->_redirect('*/*/index');
    }

    public function actOnTaskAction()
    {
        $task_id = $this->getRequest()->getParam($this->getObjectParamName());
        try
        {
            $queueTask = Mage::getModel($this->getObjectClassname())->load($task_id);
            if ((!is_object($queueTask)) || (!$queueTask->getId()))
            {
                throw new Exception('An invalid Task Id was passed to the Process Queue Controller: ' . $task_id);
            }
        }
        catch(Exception $e)
        {
            $error_message = sprintf(self::EXCEPTION_LOAD_TASK, $task_id, $e->getMessage());
            Mage::log($error_message);
            Mage::getSingleton('adminhtml/session')->addError($this->__(self::GENERIC_ADMIN_FACING_ERROR_MESSAGE));
            $exception = new Dunagan_Base_Controller_Varien_Exception($error_message);
            $exception->prepareRedirect('*/*/index');
            throw $exception;
        }

        try
        {
            $this->getQueueTaskProcessor()->processQueueTask($queueTask);
        }
        catch(Exception $e)
        {
            $error_message = sprintf(self::EXCEPTION_ACT_ON_TASK, $task_id, $e->getMessage());
            Mage::log($error_message);
            Mage::getSingleton('adminhtml/session')->addError($this->__($error_message));
            $exception = new Dunagan_Base_Controller_Varien_Exception($error_message);
            $exception->prepareRedirect('*/*/index');
            throw $exception;
        }

        $action_text = $queueTask->getActionText();
        $notice_message = sprintf(self::NOTICE_TASK_ACTION, $action_text, $task_id);
        Mage::getSingleton('adminhtml/session')->addNotice($this->__($notice_message));
        $this->_redirect('*/*/index');
    }

    public function clearAllTasksAction()
    {
        $task_codes = $this->_getTaskCodesParam();
        $redirect_route = $this->getRequest()->getParam('redirect_route');
        try
        {
            $rows_deleted = Mage::getResourceSingleton('dunagan_process_queue/task')
                                ->deleteAllTasks($task_codes);
        }
        catch(Exception $e)
        {
            $task_codes_string = implode(', ', $task_codes);
            $error_message = $this->__(self::ERROR_CLEARING_ALL_TASKS, $task_codes_string, $e->getMessage());
            Mage::getSingleton('dunagan_process_queue/log')->logQueueProcessorError($error_message);
            $this->_getAdminHelper()->throwRedirectException($error_message, $redirect_route);
        }

        if (!empty($task_code))
        {
            $success_message = $this->__(self::SUCCESS_CLEARED_ALL_TASKS_WITH_CODE, $task_code);
        }
        else
        {
            $success_message = $this->__(self::SUCCESS_CLEARED_ALL_TASKS);
        }

        $this->_getAdminHelper()->addAdminSuccessMessage($success_message);
        $this->_redirect($redirect_route);
    }

    public function clearSuccessfulTasksAction()
    {
        $task_codes = $this->_getTaskCodesParam();
        $redirect_route = $this->getRequest()->getParam('redirect_route');
        try
        {
            $rows_deleted = Mage::getResourceSingleton('dunagan_process_queue/task')
                                    ->deleteSuccessfulTasks($task_codes);
        }
        catch(Exception $e)
        {
            $task_codes_string = implode(', ', $task_codes);
            $error_message = $this->__(self::ERROR_CLEARING_SUCCESSFUL_TASKS, $task_codes_string, $e->getMessage());
            Mage::getSingleton('dunagan_process_queue/log')->logQueueProcessorError($error_message);
            $this->_getAdminHelper()->throwRedirectException($error_message, $redirect_route);
        }

        if (!empty($task_code))
        {
            $success_message = $this->__(self::SUCCESS_CLEARED_SUCCESSFUL_TASKS_WITH_CODE, $task_code);
        }
        else
        {
            $success_message = $this->__(self::SUCCESS_CLEARED_SUCCESSFUL_TASKS);
        }

        $this->_getAdminHelper()->addAdminSuccessMessage($success_message);
        $this->_redirect($redirect_route);
    }

    /**
     * @return array|null
     */
    protected function _getTaskCodesParam()
    {
            $task_codes_param = $this->getRequest()->getParam('task_codes', null);
            $task_codes = explode(';', $task_codes_param);
            if (!is_array($task_codes) || empty($task_codes))
                {
                    return null;
        }

        return $task_codes;
    }

    /**
     * Allow Queue Tasks to be created via these forms
     *
     * @param $objectToCreate
     * @param $posted_object_data
     * @return mixed
     */
    public function validateDataAndCreateObject($objectToCreate, $posted_object_data)
    {
        $objectToCreate->setLastExecutedAt(null);
        return $objectToCreate->addData($posted_object_data);
    }

    public function validateDataAndUpdateObject($objectToUpdate, $posted_object_data)
    {
        // Only the status field should have been passed
        $new_status = isset($posted_object_data['status']) ? $posted_object_data['status'] : null;
        if (!is_null($new_status))
        {
            if (!$this->canAdminUpdateStatus())
            {
                $error_message = sprintf(self::ERROR_UPDATE_STATUS_UNALLOWED);
                Mage::getSingleton('adminhtml/session')->addError($this->__($error_message));
                $exception = new Dunagan_Base_Controller_Varien_Exception($error_message);
                $exception->prepareRedirect('*/*/index');
                throw $exception;
            }
            $objectToUpdate->setStatus($new_status);
        }

        return $objectToUpdate;
    }

    public function loadBlocksBeforeGrid()
    {
        $task_index_block_classname = $this->getCompleteClassnameBySuffix($this->getHeaderBlockName());
        $this->_addContent($this->getLayout()->createBlock($task_index_block_classname));

        return $this;
    }

    public function getQueueTaskProcessor()
    {
        return Mage::helper('dunagan_process_queue/task_processor');
    }

    public function getHeaderBlockName()
    {
        return 'adminhtml_index';
    }

    public function getCodesToFilterBy()
    {
        return array();
    }

    /**
     * This method expected to be overwritten
     *
     * @return bool
     */
    public function canAdminUpdateStatus()
    {
        $update_status_acl_path  = $this->getUpdateStatusACLPath();
        if (!is_null($update_status_acl_path))
        {
            return Mage::getSingleton('admin/session')->isAllowed($update_status_acl_path);
        }

        return $this->_isAllowed();
    }

    public function getUpdateStatusACLPath()
    {
        return null;
    }

    public function getModuleGroupname()
    {
        return 'dunagan_process_queue';
    }

    public function getControllerActiveMenuPath()
    {
        return 'system/dunagan_process_queue';
    }

    public function getModuleInstanceDescription()
    {
        return 'Process Queue Tasks';
    }

    public function getIndexBlockName()
    {
        return 'adminhtml_task_index';
    }

    public function getObjectParamName()
    {
        return 'task';
    }

    public function getObjectDescription()
    {
        return 'Task';
    }

    public function getModuleInstance()
    {
        return 'task';
    }

    public function getFormBlockName()
    {
        return 'adminhtml_task';
    }

    public function getIndexActionsController()
    {
        return 'DunaganProcessQueue_index';
    }

    /**
     * @return Dunagan_Base_Helper_Admin
     */
    protected function _getAdminHelper()
    {
        if (is_null($this->_adminHelper))
        {
            $this->_adminHelper = Mage::helper('dunagan_base/admin');
        }

        return $this->_adminHelper;
    }
}
