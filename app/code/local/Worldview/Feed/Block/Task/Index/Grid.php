<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 11/10/15
 */

class Worldview_Feed_Block_Task_Index_Grid extends Dunagan_ProcessQueue_Block_Adminhtml_Task_Index_Grid
{
    public function getCodesToFilterBy()
    {
        return array(Worldview_Feed_Model_Task_Retrieve_Article::TASK_CODE);
    }

    public function escapeStatusMessage()
    {
        return false;
    }

    public function getStatusMessageStringLimit()
    {
        return 1000000;
    }
}
