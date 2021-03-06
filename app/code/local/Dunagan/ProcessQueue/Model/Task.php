<?php
/**
 * Author: Sean Dunagan
 * Created: 8/13/15
 * Class Dunagan_ProcessQueue_Model_Task
 */

class Dunagan_ProcessQueue_Model_Task
    extends Mage_Core_Model_Abstract
    implements Dunagan_ProcessQueue_Model_Task_Interface
{
    const ERROR_INVALID_OBJECT_CLASS = 'The specified object class %s does not refer to any existing classes in the system';
    const ERROR_METHOD_DOES_NOT_EXIST = 'Method %s does exist on object of class %s';

    const STATUS_PENDING = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETE = 3;
    const STATUS_ERROR = 4;
    const STATUS_ABORTED = 5;

    protected $_valid_statuses = array(self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_COMPLETE, self::STATUS_ERROR, self::STATUS_ABORTED);

    public function getStatus()
    {
        return parent::getStatus();
    }

    public function attemptUpdatingRowAsProcessing()
    {
        return $this->getResource()->attemptUpdatingRowAsProcessing($this);
    }

    public function selectForUpdate()
    {
        return $this->getResource()->selectForUpdate($this);
    }

    public function executeTask()
    {
        $object_class = $this->getObject();
        $object = Mage::getModel($object_class);
        if (!is_object($object))
        {
            $error_message = Mage::helper('dunagan_process_queue')->__(self::ERROR_INVALID_OBJECT_CLASS, $object_class);
            throw new Exception($error_message);
        }

        $method = $this->getMethod();
        if (!method_exists($object, $method))
        {
            $error_message = Mage::helper('dunagan_process_queue')->__(self::ERROR_METHOD_DOES_NOT_EXIST, $method, $object_class);
            throw new Exception($error_message);
        }

        $argumentsObject = $this->getArgumentsObject();

        // We don't know for sure what is being returned here
        $methodCallbackResult = $object->$method($argumentsObject);

        // If the method didn't return a Task_Result object
        if (!($methodCallbackResult instanceof Dunagan_ProcessQueue_Model_Task_Result_Interface))
        {
            $methodCallbackResultToReturn = Mage::getModel('dunagan_process_queue/task_result');
            $methodCallbackResultToReturn->setMethodCallbackResult($methodCallbackResult);
            // Assume completion
            $methodCallbackResultToReturn->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_COMPLETE);

            return $methodCallbackResultToReturn;
        }
        // Else return the Task_Result $methodCallbackResult
        return $methodCallbackResult;
    }

    public function actOnTaskResult(Dunagan_ProcessQueue_Model_Task_Result_Interface $taskExecutionResult)
    {
        $execution_status = $taskExecutionResult->getTaskStatus();
        if (!$this->isStatusValid($execution_status))
        {
            // Assume completion
            $execution_status = self::STATUS_COMPLETE;
        }

        $status_message = $taskExecutionResult->getTaskStatusMessage();
        $this->getResource()->setExecutionStatusForTask($execution_status, $this, $status_message);
        // TODO Log error message if $execution_status isn't a valid status
    }

    public function setTaskAsErrored($error_message = null)
    {
        return $this->getResource()->setTaskAsErrored($this, $error_message);
    }

    protected function _construct()
    {
        $this->_init('dunagan_process_queue/task');
    }

    public function isStatusValid($status)
    {
        return in_array($status, $this->_valid_statuses);
    }

    public function getActionText()
    {
        $status = $this->getStatus();
        if (!$this->isStatusValid($status))
        {
            return '';
        }

        switch($status)
        {
            case self::STATUS_ERROR:
                return 'Retry';
            case self::STATUS_PENDING:
                return 'Execute';
            default:
                return '';
        }

        return '';
    }

    protected function _beforeSave()
    {
        $status = $this->getStatus();
        if (!$this->isStatusValid($status))
        {
            // Default to Pending, assume task is just being created
            $this->setStatus(self::STATUS_PENDING);
        }

        $created_at = $this->getCreatedAt();
        if (empty($created_at))
        {
            $current_gmt_timestamp = Mage::getSingleton('core/date')->gmtTimestamp();
            $this->setCreatedAt($current_gmt_timestamp);
        }

        return parent::_beforeSave();
    }

    public function getArgumentsObject()
    {
        $serialized_arguments_object_string = $this->getSerializedArgumentsObject();
        $argumentsObject = unserialize($serialized_arguments_object_string);

        if (!is_object($argumentsObject))
        {
            $argumentsObject = new stdClass();
        }

        return $argumentsObject;
    }

    public function setTaskAsCompleted($success_message = null)
    {
        return $this->getResource()->setTaskAsCompleted($this, $success_message);
    }

    protected function _returnPendingCallbackResult($notice_message)
    {
        $methodCallbackResultToReturn = Mage::getModel('dunagan_process_queue/task_result');
        /* @var $methodCallbackResultToReturn Dunagan_ProcessQueue_Model_Task_Result */
        $methodCallbackResultToReturn->setTaskStatusMessage($notice_message);
        $methodCallbackResultToReturn->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_PENDING);

        return $methodCallbackResultToReturn;
    }

    protected function _returnSuccessCallbackResult($success_message)
    {
        $methodCallbackResultToReturn = Mage::getModel('dunagan_process_queue/task_result');
        /* @var $methodCallbackResultToReturn Dunagan_ProcessQueue_Model_Task_Result */
        $methodCallbackResultToReturn->setTaskStatusMessage($success_message);
        $methodCallbackResultToReturn->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_COMPLETE);

        return $methodCallbackResultToReturn;
    }

    protected function _returnErrorCallbackResult($error_message)
    {
        $methodCallbackResultToReturn = Mage::getModel('dunagan_process_queue/task_result');
        /* @var $methodCallbackResultToReturn Dunagan_ProcessQueue_Model_Task_Result */
        $methodCallbackResultToReturn->setTaskStatusMessage($error_message);
        $methodCallbackResultToReturn->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_ERROR);

        return $methodCallbackResultToReturn;
    }

    protected function _throwRollbackErrorException(Exception $e)
    {
        $exception_message = $e->getMessage();
        $rollbackErrorException = new Dunagan_ProcessQueue_Model_Exception_Rollback_Error($exception_message);
        $rollbackErrorException->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_ERROR);
        $rollbackErrorException->setTaskStatusMessage($exception_message);
        throw $rollbackErrorException;
    }

    protected function _returnAbortCallbackResult($error_message)
    {
        $methodCallbackResultToReturn = Mage::getModel('dunagan_process_queue/task_result');
        /* @var $methodCallbackResultToReturn Dunagan_ProcessQueue_Model_Task_Result */
        $methodCallbackResultToReturn->setTaskStatusMessage($error_message);
        $methodCallbackResultToReturn->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_ABORTED);

        return $methodCallbackResultToReturn;
    }

    protected function _throwRollbackAbortException(Exception $e)
    {
        $exception_message = $e->getMessage();
        $rollbackErrorException = new Dunagan_ProcessQueue_Model_Exception_Rollback_Abort($exception_message);
        $rollbackErrorException->setTaskStatus(Dunagan_ProcessQueue_Model_Task::STATUS_ABORTED);
        $rollbackErrorException->setTaskStatusMessage($exception_message);
        throw $rollbackErrorException;
    }
}
