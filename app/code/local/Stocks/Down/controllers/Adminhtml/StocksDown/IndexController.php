<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/8/16
 */

class Stocks_Down_Adminhtml_StocksDown_IndexController
    extends Dunagan_Base_Controller_Adminhtml_Abstract
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    public function getModuleGroupname()
    {
        return 'stocks_down';
    }

    public function getControllerActiveMenuPath()
    {
        return 'stocks/down/view_down_stocks';
    }

    public function getModuleInstanceDescription()
    {
        return 'Down Stocks';
    }

    public function getIndexBlockName()
    {
        return 'adminhtml_index';
    }

    public function getIndexActionsController()
    {
        return 'StocksDown_index';
    }

    public function getObjectParamName()
    {
        return 'down_stock';
    }

    public function getModuleInstance()
    {
        return 'stock';
    }
}
