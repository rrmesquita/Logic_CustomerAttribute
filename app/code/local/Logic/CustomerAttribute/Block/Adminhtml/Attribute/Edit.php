<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Block_Adminhtml_Attribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'attribute_id';
        $this->_blockGroup = 'lgc_customerattribute';
        $this->_controller = 'adminhtml_attribute';
        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('lgc_customerattribute')->__('Save Attribute'));
        $this->_updateButton('delete', 'label', Mage::helper('lgc_customerattribute')->__('Delete Attribute'));
        if (! Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('entity_attribute') && Mage::registry('entity_attribute')->getId()) {
            return Mage::helper('lgc_customerattribute')->__('Edit Customer Attribute "%s"', $this->htmlEscape(Mage::registry('entity_attribute')->getData('frontend_label')));
        } else {
            return Mage::helper('lgc_customerattribute')->__('Add Customer Attribute');
        }
    }
}
