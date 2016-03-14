<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Dunagan_Base_Block_Google_Graphs extends Mage_Core_Block_Template
{
    public function _toHtml()
    {
        $html_to_output = '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
        $html_to_output .= '<script type="text/javascript">google.charts.load(\'current\', {\'packages\':[\'line\']});</script>';
        return $html_to_output;
    }
}
