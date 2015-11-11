<?php
/**
 * Author: Sean Dunagan
 * Created: 4/11/15
 *
 * class Worldview_Feed_IndexController
 */

require_once('Dunagan/ProcessQueue/controllers/Adminhtml/DunaganProcessQueue/IndexController.php');
class Worldview_Feed_Adminhtml_WorldviewFeed_IndexController
    extends Dunagan_ProcessQueue_Adminhtml_DunaganProcessQueue_IndexController
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    public function loadBlocksBeforeGrid()
    {
        $this->_addContent($this->getLayout()->createBlock('worldview_feed/process_index'));
        $this->_addContent($this->getLayout()->createBlock('worldview_feed/task_summary'));

        return $this;
    }

    public function getCodesToFilterBy()
    {
        return array(Worldview_Feed_Model_Task_Retrieve_Article::TASK_CODE);
    }

    public function getIndexActionsController()
    {
        return 'WorldviewFeed_index';
    }

    public function getBlocksModuleGroupname()
    {
        return 'worldview_feed';
    }

    public function getFormBlockName()
    {
        return 'task';
    }

    public function getIndexBlockName()
    {
        return 'task_index';
    }

    public function getControllerActiveMenuPath()
    {
        return 'worldview/feeds/process';
    }
}
