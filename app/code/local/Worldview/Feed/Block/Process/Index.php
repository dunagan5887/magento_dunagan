<?php
/**
 * Author: Sean Dunagan
 * Created: 4/11/15
 *
 * class Mage_Adminhtml_Block_Widget_Container
 */

class Worldview_Feed_Block_Process_Index
    extends Dunagan_Base_Block_Adminhtml_Widget_Container
    implements Dunagan_Base_Block_Adminhtml_Widget_Container_Interface
{
    const HEADER_TEXT = 'This page demonstrates the ProcessQueue functionality at work';

    public function getDefinedHeaderText()
    {
        return self::HEADER_TEXT;
    }

    public function getObjectId()
    {
        return 'worldview_feed_process_container';
    }

    public function getActionButtonsToRender()
    {
        $article_retrieval_process_button = array(
            'action_url' => Mage::getModel('adminhtml/url')->getUrl('adminhtml/WorldviewFeed_process/articleRetrieval'),
            'label' => 'Retrieve Articles'
        );

        return array('article_retrieval' => $article_retrieval_process_button);
    }
}
