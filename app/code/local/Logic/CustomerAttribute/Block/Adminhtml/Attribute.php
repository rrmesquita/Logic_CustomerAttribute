<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Block_Adminhtml_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'lgc_customerattribute';
        $this->_controller = 'adminhtml_attribute';
        $this->_headerText = Mage::helper('lgc_customerattribute')->__('Manage Customer Attributes');
        $this->_addButtonLabel = Mage::helper('lgc_customerattribute')->__('Add New Attribute');

        parent::__construct();
    }
}
