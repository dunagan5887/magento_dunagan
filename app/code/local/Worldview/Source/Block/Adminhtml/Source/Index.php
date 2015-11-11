<?php
/**
 * Author: Sean Dunagan
 * Created: 4/7/15
 *
 * Class Worldview_Source_Block_Adminhtml_Source_Grid_Index
 */

class Worldview_Source_Block_Adminhtml_Source_Index
    extends Dunagan_Base_Block_Adminhtml_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        // TODO ALLOW FOR ADDING SOURCES TO DEMONSTRATE FUNCTIONALITY EXISTS
        // Don't want to allow for the addition of sources via admin panel at this time
        $this->_removeButton('add');
    }

    public function getActionButtonsToRender()
    {
        $article_retrieval_process_button = array(
            'action_url' => Mage::getModel('adminhtml/url')->getUrl('adminhtml/WorldviewFeed_process/articleRetrieval'),
            'label' => 'Retrieve Articles From Sources'
        );

        return array('article_retrieval' => $article_retrieval_process_button);
    }
}
