<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 12/10/15
 */

class Dunagan_Base_Helper_Admin extends Mage_Core_Helper_Data
{
    public function throwRedirectException($error_message, $redirect_path, $redirect_arguments = array())
    {
        $this->addAdminErrorMessage($error_message);
        $exception = new Dunagan_Base_Controller_Varien_Exception($error_message);
        Mage::logException($exception);
        Mage::log($error_message);
        $exception->prepareRedirect($redirect_path, $redirect_arguments);
        throw $exception;
    }

    public function addAdminSuccessMessage($success_message)
    {
        return Mage::getSingleton('adminhtml/session')->addSuccess($this->__($success_message));
    }

    public function addAdminErrorMessage($error_message)
    {
        return Mage::getSingleton('adminhtml/session')->addError($this->__($error_message));

    }
}
