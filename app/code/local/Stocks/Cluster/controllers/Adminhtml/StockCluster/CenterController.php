<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Adminhtml_StockCluster_CenterController
    extends Dunagan_Base_Controller_Adminhtml_Abstract
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    public function getModuleGroupname()
    {
        return 'stocks_cluster';
    }

    public function getControllerActiveMenuPath()
    {
        return 'stocks/clusters/view_centers';
    }

    public function getModuleInstanceDescription()
    {
        return 'Stock Cluster Centers';
    }

    public function getIndexBlockName()
    {
        return 'adminhtml_center_index';
    }

    public function getIndexActionsController()
    {
        return 'StockCluster_center';
    }

    public function getObjectParamName()
    {
        return 'cluster_center';
    }

    public function getModuleInstance()
    {
        return 'center';
    }
}
