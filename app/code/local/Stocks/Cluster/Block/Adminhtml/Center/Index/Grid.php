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
        /* @var $collection Stocks_Cluster_Model_Mysql4_Center_Collection */

        $line_graph_points_table = $collection->getResource()->getTable('stocks_cluster/line_graph_points');

        $collection->getSelect()->join(
            array('line_graph_points' => $line_graph_points_table),
            'line_graph_points.cluster_id=main_table.cluster_id'
        );

        $cluster_symbol_xref_table = $collection->getResource()->getTable('stocks_cluster/symbol_xref');

        $collection->getSelect()->join(
            array('xref' => $cluster_symbol_xref_table),
            'xref.cluster_id=main_table.cluster_id',
            array('number_of_symbols' => 'count(*)')
        );

        $collection->getSelect()->group('xref.cluster_id');

        parent::setCollection($collection);
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
            if ($column->getFilterConditionCallback()) {
                call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
            } else {
                $cond = $column->getFilter()->getCondition();
                if ($field && isset($cond)) {
                    $this->getCollection()->addFieldToFilter($field , $cond);
                }
            }
        }
        return $this;
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

        $this->addColumn('number_of_symbols', array(
            'header'    => $this->_getTranslationHelper()->__('# of Symbols'),
            'align'     => 'left',
            'width'     => '50',
            'index'     => 'number_of_symbols',
            'getter'    => 'getNumberOfSymbols',
            'type'      => 'range',
            'filter_condition_callback' => array($this, '_setRangeFilterAsHavingClause'),
            'having_param' => 'count(*)'
        ));

        $this->addColumn('performance_graph', array(
            'header'    => $this->_getTranslationHelper()->__('Performance Graph'),
            'align'     => 'left',
            'index'     => 'delta_percentage',
            'type'      => 'text',
            'renderer'  => 'stocks_cluster/Adminhtml_Widget_Grid_Column_Renderer_Graph',
            'filter'    => false,
            'sortable'  => false,
            'id_accessor' => 'getClusterId'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        $object_param_name = 'cluster';
        return $this->getUrl('adminhtml/StockCluster_member/index', array($object_param_name => $row->getClusterId()));
    }
}
