<?php
/**
 * Author: Sean Dunagan
 * Created: 4/6/15
 * Class Worldview_Article_Block_Adminhtml_Source_Container_Grid
 */

class Stocks_Cluster_Block_Adminhtml_Member_Index_Grid
    extends Dunagan_Base_Block_Adminhtml_Widget_Grid
{
    protected $_defaultSort     = 'symbol';
    protected $_defaultDir      = 'asc';

    public function setCollection($collection)
    {
        /* @var $collection Stocks_Cluster_Model_Mysql4_Xref_Collection */

        $line_graph_points_table = $collection->getResource()->getTable('stocks_symbol/line_graph_points');

        $collection->getSelect()->join(
            array('line_graph_points' => $line_graph_points_table),
            'line_graph_points.symbol=main_table.symbol'
        );

        $cluster_id_param = $this->getParam('cluster');
        $collection->getSelect()->where('cluster_id=?', $cluster_id_param);

        parent::setCollection($collection);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('symbol', array(
            'header'    => $this->_getTranslationHelper()->__('Symbol'),
            'width'     => '100',
            'align'     => 'left',
            'index'     => 'main_table.symbol',
            'getter'    => 'getSymbol',
            'type'      => 'text'
        ));

        $this->addColumn('performance_graph', array(
            'header'    => $this->_getTranslationHelper()->__('Performance Graph'),
            'align'     => 'left',
            'type'      => 'text',
            'renderer'  => 'stocks_cluster/Adminhtml_Widget_Grid_Column_Renderer_Graph',
            'filter'    => false,
            'sortable'  => false,
            'id_accessor' => 'getSymbol'
        ));

        return parent::_prepareColumns();
    }
}
