<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Block_Adminhtml_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customerattribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('lgc_customerattribute')->__('Attribute Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main_section', array(
            'label' => Mage::helper('lgc_customerattribute')->__('Properties'),
            'title' => Mage::helper('lgc_customerattribute')->__('Properties'),
            'content' => $this->getLayout()
                               ->createBlock('lgc_customerattribute/adminhtml_attribute_edit_tab_main')
                               ->toHtml()
        ));

        $this->addTab('options_section', array(
            'label' => Mage::helper('lgc_customerattribute')->__('Manage Label / Options'),
            'title' => Mage::helper('lgc_customerattribute')->__('Manage Label / Options'),
            'content' => $this->getLayout()
                               ->createBlock('lgc_customerattribute/adminhtml_attribute_edit_tab_options')
                               ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}
