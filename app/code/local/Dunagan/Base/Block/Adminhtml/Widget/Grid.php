<?php

/**
 * Author: Sean Dunagan
 * Created: 04/06/2015
 *
 * Class Worldview_Base_Block_Adminhtml_Widget_Grid
 *
 * This class expects the controller to a descendant of class Worldview_Base_Controller_Adminhtml_Abstract
 */

class Dunagan_Base_Block_Adminhtml_Widget_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * This class assumes that the controller loading it extends the
     *      Dunagan_Base_Controller_Adminhtml_Abstract class
     *
     * @return Dunagan_Base_Controller_Adminhtml_Abstract
     */
    public function getAction()
    {
        return parent::getAction();
    }

    protected $_translationHelper = null;

    public function __construct()
    {
        parent::__construct();
        $controllerAction = $this->getAction();
        $grid_path = str_replace('/', '_', $controllerAction->getControllerActiveMenuPath());
        $grid_id = $controllerAction->getBlocksModuleGroupname() . '_' . $grid_path;

        $this->setId($grid_id);
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $object_classname = $this->getAction()->getObjectClassname();

        $collection = Mage::getModel($object_classname)->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        $object_param_name = $this->getAction()->getObjectParamName();
        $edit_uri_path = $this->getAction()->getUriPathForIndexAction('edit');
        return $this->getUrl($edit_uri_path, array($object_param_name => $row->getId()));
    }

    public function getGridUrl()
    {
        $uri_path = $this->getAction()->getUriPathForIndexAction('ajaxGrid');
        return $this->getUrl($uri_path);
    }

    protected function _addTextGridColumn($index, $label, $html_id = null, $align = 'left')
    {
        if (is_null($html_id))
        {
            $html_id = $index;
        }

        $this->addColumn($html_id,
            array(
                'header'  => $this->_getTranslationHelper()->__($label),
                'align'   => $align,
                'index'   => $index,
                'type'    => 'text'
            )
        );
    }

    protected function _addDatetimeGridColumn($index, $label, $html_id = null, $align = 'left')
    {
        if (is_null($html_id))
        {
            $html_id = $index;
        }

        $this->addColumn($html_id,
            array(
                'header'  => $this->_getTranslationHelper()->__($label),
                'align'   => $align,
                'index'   => $index,
                'type'   => 'datetime',
                'renderer' => 'dunagan_base/adminhtml_widget_grid_column_renderer_datetime'
            )
        );
    }

    protected function _addOptionsGridColumn($index, $label, $options_array, $html_id = null, $align = 'left')
    {
        if (is_null($html_id))
        {
            $html_id = $index;
        }

        $this->addColumn($html_id,
            array(
                'header'  => $this->_getTranslationHelper()->__($label),
                'align'   => $align,
                'index'   => $index,
                'type'    => 'options',
                'options' => $options_array
            )
        );
    }

    protected function _addBooleanOptionsGridColumn($index, $label, $html_id = null, $align = 'left')
    {
        $boolean_options_array = Mage::getModel('eav/entity_attribute_source_boolean')->getOptionArray();
        $this->_addOptionsGridColumn($index, $label, $boolean_options_array, $html_id, $align);
    }

    protected function _addActionGridColumn($index, $label, $renderer_classname, $width = '50px', $align = 'center',
                                            $getter = 'getId', $html_id = null)
    {
        if (is_null($html_id))
        {
            $html_id = $index;
        }

        $this->addColumn($html_id,
            array(
                'header'   => $this->_getTranslationHelper()->__($label),
                'align'    => $align,
                'width'    => $width,
                'type'     => 'action',
                'getter'   => $getter,
                'renderer' => $renderer_classname,
                'filter'   => false,
                'sortable' => false
            )
        );
    }

    /**
     * @param Stocks_Cluster_Model_Mysql4_Center_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     */
    protected function _setRangeFilterAsHavingClause($collection, $column)
    {
        $filter = $column->getFilter();
        $filter_value = $filter->getValue();

        if((!is_array($filter_value)) || (empty($filter_value)))
        {
            return;
        }

        $from_value = isset($filter_value['from']) ? $filter_value['from'] : '';
        $to_value = isset($filter_value['to']) ? $filter_value['to'] : '';

        $having_param = $column->getHavingParam();
        if (!empty($from_value))
        {
            $clause = $having_param . ' >= ?';
            $collection->getSelect()->having($clause, $from_value);
        }
        if (!empty($to_value))
        {
            $clause = $having_param . ' <= ?';
            $collection->getSelect()->having($clause, $to_value);
        }
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getTranslationHelper()
    {
        if (is_null($this->_translationHelper))
        {
            $controllerAction = $this->getAction();
            $this->_translationHelper = $controllerAction->getModuleHelper();
        }

        return $this->_translationHelper;
    }
}
