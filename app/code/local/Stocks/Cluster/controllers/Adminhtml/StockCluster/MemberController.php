<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Adminhtml_StockCluster_MemberController
    extends Dunagan_Base_Controller_Adminhtml_Abstract
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    public function getModuleGroupname()
    {
        return 'stocks_cluster';
    }

    public function getControllerActiveMenuPath()
    {
        return 'stocks/clusters/view_members';
    }

    public function getModuleInstanceDescription()
    {
        return 'Stock Cluster Members';
    }

    public function getIndexBlockName()
    {
        return 'adminhtml_member_index';
    }

    public function getIndexActionsController()
    {
        return 'StockCluster_member';
    }

    public function getObjectParamName()
    {
        return 'cluster_member';
    }

    public function getModuleInstance()
    {
        return 'xref';
    }
}
