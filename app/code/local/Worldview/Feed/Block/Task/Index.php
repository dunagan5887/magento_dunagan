<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 11/10/15
 */

class Worldview_Feed_Block_Task_Index extends Dunagan_ProcessQueue_Block_Adminhtml_Task_Index
{
    protected $_show_clear_tasks_buttons = false;

    public function getTaskJobCodes()
    {
        return array('worldview_retrieve_articles');
    }
}
