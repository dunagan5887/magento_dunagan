<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Block_Adminhtml_Widget_Grid_Column_Renderer_Graph
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_graphPointsHelper = null;

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('dunagan/widget/grid/column/renderer/graph.phtml');
    }

    public function render(Varien_Object $row)
    {
        $this->setRowToRender($row);

        return $this->_toHtml();
    }

    public function getHtmlIdSuffix()
    {
        $id_accessor = $this->getColumn()->getIdAccessor();
        return $this->getRowToRender()->$id_accessor();
    }

    public function getOrderedGraphPoints()
    {
        $row = $this->getRowToRender();
        $row_data = $row->getData();

        $ordered_graph_points_array = $this->_getGraphPointsHelper()->getOrderedGraphPoints($row_data);

        return $ordered_graph_points_array;
    }

    /**
     * @return Stocks_Cluster_Helper_Graph
     */
    protected function _getGraphPointsHelper()
    {
        if (is_null($this->_graphPointsHelper))
        {
            $this->_graphPointsHelper = Mage::helper('stocks_cluster/graph');
        }

        return $this->_graphPointsHelper;
    }
}
