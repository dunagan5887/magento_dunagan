<?php
/**
 * Author: Sean Dunagan
 * Created: 9/22/15
 */

class Dunagan_Base_Block_Adminhtml_Widget_Grid_Column_Renderer_Datetime
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    CONST DEFAULT_DATETIME_FORMAT = 'Y-m-d g:i:s A';

    public function render(Varien_Object $row)
    {
        $data = $this->_getValue($row);
        if (!strcmp('0000-00-00 00:00:00', $data)) {
            return '';
        }

        if (!$this->getColumn()->getDoNotConvertDatetime())
        {
            return parent::render($row);
        }

        $format = $this->getColumn()->getFormat();
        if (empty($format))
        {
            $format = self::DEFAULT_DATETIME_FORMAT;
        }

        $timestamp = strtotime($data);
        $data = date($format, $timestamp);

        return $data;
    }
}
