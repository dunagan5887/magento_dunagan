<?php
/**
 * Author: Sean Dunagan
 * Created: 04/06/2015
 *
 * Class Worldview_Base_Controller_Adminhtml_Abstract
 */

abstract class Dunagan_Base_Controller_Adminhtml_Abstract
    extends Mage_Adminhtml_Controller_Action
    implements Dunagan_Base_Controller_Adminhtml_Interface
{
    const GENERIC_CUSTOMER_FACING_ERROR = 'There was an error with your request, please try again';

    // Documentation for these abstract classes is given in Dunagan_Base_Controller_Adminhtml_Interface
    abstract public function getModuleGroupname();

    abstract public function getControllerActiveMenuPath();

    abstract public function getModuleInstanceDescription();

    abstract public function getIndexBlockName();

    abstract public function getModuleInstance();

    abstract public function getObjectParamName();

    // The following is accessible via accessor method getModuleHelper()
    protected $_moduleHelper = null;

    public function indexAction()
    {
        $module_description = $this->getModuleInstanceDescription();
        $module_block_classname = $this->getBlocksModuleGroupname() . '/' . $this->getIndexBlockName();

        $this->loadLayout()
            ->_setActiveMenuValue()
            ->_setSetupTitle($this->getModuleHelper()->__($module_description))
            ->_addBreadcrumb()
            ->_addBreadcrumb($this->getModuleHelper()->__($module_description), $this->getModuleHelper()->__($module_description))
            ->loadBlocksBeforeGrid()
            ->_addContent($this->getLayout()->createBlock($module_block_classname))
            ->loadBlocksAfterGrid()
            ->renderLayout();
    }

    /**
     * This also can be done (and probably should be) via layout.xml
     *
        <ACTION_HANDLE>
            <block type="core/text_list" name="root" output="toHtml">
                <block type="BLOCK_CLASSNAME" name="BLOCK_LAYOUT_NAME"/>
            </block>
        </ACTION_HANDLE>
     *
     */
    public function ajaxGridAction()
    {
        $this->loadLayout();

        $rootBlock = $this->getLayout()->createBlock('core/text_list', 'root', array('output' => "toHtml"));
        $grid_block_classname = $this->getBlocksModuleGroupname() . '/' . $this->getIndexBlockName() . '_grid';
        $gridBlock = $this->getLayout()->createBlock($grid_block_classname, 'ajax.grid');
        $rootBlock->append($gridBlock, 'ajax.grid');

        $this->renderLayout();
    }

    /**
     * Returns the uri path for whatever controller action is passed in
     *
     * @param $action
     * @return string
     */
    public function getUriPathForIndexAction($action)
    {
        $uri_path = sprintf('%s/%s/%s', $this->getModuleRouterFrontname(), $this->getIndexActionsController(), $action);
        return $uri_path;
    }

    public function getObjectClassname()
    {
        $objects_module_instance = $this->getModuleInstance();
        $objects_module = $this->getModuleGroupname();
        $object_classname = $objects_module . '/' . $objects_module_instance;

        return $object_classname;
    }

    /**
     * As of Magento security patch supee-6788, all admin frontnames should be adminhtml
     *
     * @return string
     */
    public function getModuleRouterFrontname()
    {
        return 'adminhtml';
    }

    protected function _setSetupTitle($title)
    {
        try
        {
            $this->_title($title);
        }
        catch (Exception $e)
        {
            Mage::logException($e);
        }
        return $this;
    }

    protected function _addBreadcrumb($label = null, $title = null, $link=null)
    {
        $module_description = $this->getModuleInstanceDescription();

        if (is_null($label))
        {
            $label = $this->getModuleHelper()->__($module_description);
        }
        if (is_null($title))
        {
            $title = $this->getModuleHelper()->__($module_description);
        }
        return parent::_addBreadcrumb($label, $title, $link);
    }

    protected function _setActiveMenuValue()
    {
        return parent::_setActiveMenu($this->getControllerActiveMenuPath());
    }

    protected function _isAllowed()
    {
        if(!Mage::getSingleton('admin/session')->isAllowed($this->getAclPath()))
        {
            return false;
        }

        return true;
    }

    public function getBlocksModuleGroupname()
    {
        return $this->getModuleGroupname();
    }

    public function loadBlocksBeforeGrid()
    {
        return $this;
    }

    public function loadBlocksAfterGrid()
    {
        return $this;
    }

    public function getCompleteClassnameBySuffix($classname_suffix)
    {
        return $this->getModuleGroupname() . '/' . $classname_suffix;
    }

    public function getAclPath()
    {
        return $this->getControllerActiveMenuPath();
    }

    protected function _logExceptionAndRedirectToGrid(Exception $exceptionToLog)
    {
        Mage::log($exceptionToLog->getMessage());
        Mage::logException($exceptionToLog);
        $this->_getSession()->addError($this->getModuleHelper()->__($exceptionToLog->getMessage()));
        $grid_route = $this->getUriPathForIndexAction('index');
        return $this->_redirect($grid_route);
    }

    protected function _showGenericCustomerFacingSessionErrorAndRedirectToGrid()
    {
        $this->_getSession()->addError($this->getModuleHelper()->__(self::GENERIC_CUSTOMER_FACING_ERROR));
        $grid_route = $this->getUriPathForIndexAction('index');
        return $this->_redirect($grid_route);
    }

    public function getModuleHelper()
    {
        if (is_null($this->_moduleHelper))
        {
            $module_groupname = $this->getModuleGroupname();
            $this->_moduleHelper = Mage::helper($module_groupname);
        }

        return $this->_moduleHelper;
    }
}
