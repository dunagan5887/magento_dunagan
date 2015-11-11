<?php
/**
 * Author: Sean Dunagan
 * Created: 8/14/15
 */

class Dunagan_ProcessQueue_Model_Task_Result
    implements Dunagan_ProcessQueue_Model_Task_Result_Interface
{
    protected $_task_status = null;
    protected $_task_status_message = null;

    /**
     * To be used when an execution of a callback in Dunagan_ProcessQueue_Model_Task::executeTask() does not return
     *      an object of type Dunagan_ProcessQueue_Model_Task_Result
     */
    protected $_methodCallbackResult = null;

    /**
     * Denotes whether the task execution failed to the point that all related database transactions should be rolled
     *      back. If a task returned an object inheriting this class, we assume that we don't need to roll back any/all
     *      database transactions related to the task execution
     *
     * @return bool
     */
    public function shouldTransactionBeRolledBack()
    {
        return false;
    }

    public function getMethodCallbackResult()
    {
        return $this->_methodCallbackResult;
    }

    public function setMethodCallbackResult($methodCallbackResult)
    {
        $this->_methodCallbackResult = $methodCallbackResult;
        return $this;
    }

    /**
     * Returns the status of a Dunagan_ProcessQueue_Model_Task::executeTask() call
     *
     * @return mixed - Expected to return one of the STATUS_* constants in class Dunagan_ProcessQueue_Model_Task
     */
    public function getTaskStatus()
    {
        return $this->_task_status;
    }

    /**
     * Sets the status of a Dunagan_ProcessQueue_Model_Task::executeTask() call
     *
     * @return mixed - Expected to pass in one of the STATUS_* constants in class Dunagan_ProcessQueue_Model_Task
     */
    public function setTaskStatus($task_status)
    {
        $this->_task_status = $task_status;
        return $this;
    }

    public function getTaskStatusMessage()
    {
        return $this->_task_status_message;
    }

    public function setTaskStatusMessage($task_status_message)
    {
        $this->_task_status_message = $task_status_message;
        return $this;
    }
}
