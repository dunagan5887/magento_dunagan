<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Block_Adminhtml_Member_Index
    extends Dunagan_Base_Block_Adminhtml_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('add');
    }

    public function _toHtml()
    {
        $googleGraphs = $this->getLayout()->createBlock('dunagan_base/google_graphs');

        $html_to_output = $googleGraphs->toHtml();

        $parent_html = parent::_toHtml();

        return $html_to_output . $parent_html;
    }
}
