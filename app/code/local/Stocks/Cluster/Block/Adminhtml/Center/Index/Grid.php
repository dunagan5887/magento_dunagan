<?php
/**
 * Author: Sean Dunagan
 * Created: 4/6/15
 * Class Worldview_Article_Block_Adminhtml_Source_Container_Grid
 */

class Stocks_Cluster_Block_Adminhtml_Center_Index_Grid
    extends Dunagan_Base_Block_Adminhtml_Widget_Grid
{
    protected $_defaultSort     = 'delta_percentage';
    protected $_defaultDir      = 'desc';

    public function setCollection($collection)
    {
        $line_graph_points_table = $collection->getResource()->getTable('stocks_cluster/line_graph_points');

        $collection->getSelect()->join(
            array('line_graph_points' => $line_graph_points_table),
            'line_graph_points.cluster_id=main_table.cluster_id'
        );

        parent::setCollection($collection);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('cluster_id', array(
            'header'    => $this->_getTranslationHelper()->__('Cluster ID'),
            'width'     => '100',
            'align'     => 'left',
            'index'     => 'cluster_id',
            'type'      => 'text'
        ));

        $this->addColumn('delta_percentage', array(
            'header'    => $this->_getTranslationHelper()->__('Delta'),
            'align'     => 'left',
            'width'     => '100',
            'index'     => 'delta_percentage',
            'type'      => 'range'
        ));

        $this->addColumn('performance_graph', array(
            'header'    => $this->_getTranslationHelper()->__('Performance Graph'),
            'align'     => 'left',
            'index'     => 'delta_percentage',
            'type'      => 'text',
            'renderer'  => 'stocks_cluster/Adminhtml_Widget_Grid_Column_Renderer_Graph',
            'filter'    => false,
            'sortable'  => false
        ));

        return parent::_prepareColumns();
    }
}
