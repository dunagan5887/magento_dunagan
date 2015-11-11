<?php
/**
 * Author: Sean Dunagan
 * Created: 4/11/15
 *
 * class Worldview_Feed_IndexController
 */

class Worldview_Feed_Adminhtml_WorldviewFeed_IndexController
    extends Dunagan_Base_Controller_Adminhtml_Abstract
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    public function getModuleInstance()
    {
        return 'feed';
    }

    public function getObjectParamName()
    {
        return 'feed';
    }

    public function getModuleGroupname()
    {
        return 'worldview_feed';
    }

    public function getControllerActiveMenuPath()
    {
        return 'worldview/feeds/process';
    }

    public function getModuleInstanceDescription()
    {
        return 'Feed Processor';
    }

    public function getIndexBlockName()
    {
        return 'process_index';
    }

    public function getIndexActionsController()
    {
        return 'WorldviewFeed_index';
    }
}