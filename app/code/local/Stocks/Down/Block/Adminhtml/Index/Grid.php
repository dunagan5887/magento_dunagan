<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/8/16
 */

class Stocks_Down_Block_Adminhtml_Index_Grid extends Dunagan_Base_Block_Adminhtml_Widget_Grid
{
    protected function _prepareColumns()
    {
        $this->addColumn('symbol', array(
            'header'    => $this->_getTranslationHelper()->__('Symbol'),
            'width'     => '100',
            'align'     => 'left',
            'index'     => 'symbol',
            'type'      => 'text'
        ));

        $this->addColumn('span_unit_delta_percentage_ratio', array(
            'header'    => $this->_getTranslationHelper()->__('Span Unit Delta % Ratio'),
            'align'     => 'left',
            'index'     => 'span_unit_delta_percentage_ratio',
            'type'      => 'text'
        ));

        $this->addColumn('today_price', array(
            'header'    => $this->_getTranslationHelper()->__('Today Price'),
            'align'     => 'left',
            'index'     => 'today_price',
            'type'      => 'text'
        ));

        $this->addColumn('today_unit_delta_percentage', array(
            'header'    => $this->_getTranslationHelper()->__('Today Unit Delta %'),
            'align'     => 'left',
            'index'     => 'today_unit_delta_percentage',
            'type'      => 'text'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return '#';
    }
}
