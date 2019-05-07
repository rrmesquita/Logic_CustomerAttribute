<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Block_Adminhtml_Attribute_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $attributeObject = Mage::registry('entity_attribute');
        $generalFieldset = $form->addFieldset('general_fieldset', array('legend' => Mage::helper('lgc_customerattribute')->__('General Properties')));
        $frontendFieldset = $form->addFieldset('frontend_fieldset', array('legend' => Mage::helper('lgc_customerattribute')->__('Frontend Properties')));
        $additionalFieldset = $form->addFieldset('additional_fieldset', array('legend' => Mage::helper('lgc_customerattribute')->__('Additional Properties')));
        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $generalFieldset->addField('attribute_code', 'text', array(
            'label'    => Mage::helper('lgc_customerattribute')->__('Attribute Code'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Attribute Code'),
            'class'    => sprintf('validate-code validate-length maximum-length-%d', Mage_Eav_Model_Entity_Attribute::ATTRIBUTE_CODE_MAX_LENGTH),
            'required' => true,
            'name'     => 'attribute_code',
            'note'     => Mage::helper('lgc_customerattribute')->__('For internal use. Must be unique with no spaces. Maximum length of attribute code must be less then %s symbols', Mage_Eav_Model_Entity_Attribute::ATTRIBUTE_CODE_MAX_LENGTH)
        ));

        $generalFieldset->addField('frontend_input', 'select', array(
            'name'     => 'frontend_input',
            'label'    => Mage::helper('lgc_customerattribute')->__('Attribute Type'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Attribute Type'),
            'required' => true,
            'value'    => 'text',
            'values'   => Mage::getModel('eav/adminhtml_system_config_source_inputtype')->toOptionArray()
        ));

        $frontendFieldset->addField('is_visible', 'select', array(
            'name'     => 'is_visible',
            'label'    => Mage::helper('lgc_customerattribute')->__('Visible'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Visible'),
            'values'   => $yesnoSource,
            'value'    => 1
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $field = $frontendFieldset->addField('store_view', 'multiselect', array(
                'name'     => 'store_view',
                'label'    => Mage::helper('lgc_customerattribute')->__('Store View'),
                'title'    => Mage::helper('lgc_customerattribute')->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'value'    => 0
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $frontendFieldset->addField('store_view', 'hidden', array(
                'name'      => 'store_view',
                'value'     => 0
            ));
        }

        $frontendFieldset->addField('used_in_forms', 'multiselect', array(
            'name'     => 'used_in_forms',
            'label'    => Mage::helper('lgc_customerattribute')->__('Used in Forms'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Used in Forms'),
            'values'   => array(
                array('value' => 'customer_account_create', 'label' => Mage::helper('lgc_customerattribute')->__('customer_account_create')),
                array('value' => 'customer_account_edit', 'label' => Mage::helper('lgc_customerattribute')->__('customer_account_edit')),
                array('value' => 'checkout_register', 'label' => Mage::helper('lgc_customerattribute')->__('checkout_register')),
                array('value' => 'adminhtml_customer', 'label' => Mage::helper('lgc_customerattribute')->__('adminhtml_customer')),
                array('value' => 'adminhtml_checkout', 'label' => Mage::helper('lgc_customerattribute')->__('adminhtml_checkout'))
            )
        ));

        $frontendFieldset->addField('sort_order', 'text', array(
            'label'    => Mage::helper('lgc_customerattribute')->__('Sort Order'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Sort Order'),
            'class'    => 'validate-number',
            'name'     => 'sort_order',
            'value'    => 0
        ));

        $additionalFieldset->addField('is_required', 'select', array(
            'name'     => 'is_required',
            'label'    => Mage::helper('lgc_customerattribute')->__('Required'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Required'),
            'values'   => $yesnoSource
        ));

        $additionalFieldset->addField('is_unique', 'select', array(
            'name'     => 'is_unique',
            'label'    => Mage::helper('lgc_customerattribute')->__('Unique Value'),
            'title'    => Mage::helper('lgc_customerattribute')->__('Unique Value'),
            'note'     => Mage::helper('lgc_customerattribute')->__('Not shared with other products'),
            'values'   => $yesnoSource
        ));

        if ($attributeObject->getId()) {
            $form->getElement('attribute_code')->setDisabled(1);
            $form->getElement('frontend_input')->setDisabled(1);
            if (!$attributeObject->getIsUserDefined()) {
                $form->getElement('is_unique')->setDisabled(1);
                $form->getElement('used_in_forms')->setDisabled(1);
                $form->getElement('store_view')->setDisabled(1);
            }
        }

        if ($attributeObject) {
            if (!Mage::app()->isSingleStoreMode()) {
                $storesData = array();
                $storesCollection = Mage::getModel('lgc_customerattribute/store')->getCollection();
                $storesCollection->addFieldToFilter('attribute_id', $attributeObject->getId());
                foreach($storesCollection as $store) {
                    array_push($storesData, $store->getData('store_id'));
                }
                $form->addValues(array('store_view' => $storesData));
            }
            $form->addValues($attributeObject->getData());
        } else {
            return parent::_prepareForm();
        }
    }
}
