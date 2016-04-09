<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/8/16
 */

class Stocks_Down_Block_Adminhtml_Index extends Dunagan_Base_Block_Adminhtml_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('add');
    }
}
