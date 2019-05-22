<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Adminhtml_Customer_AttributeController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/attributes/attributes');
    }

    public function preDispatch()
    {
        $this->_setForcedFormKeyActions('delete');
        parent::preDispatch();
        $this->_id             = $this->getRequest()->getParam('attribute_id');
        $this->_code           = $this->getRequest()->getParam('attribute_code');
        $this->_model          = Mage::getModel('customer/attribute');
        $this->_entityType     = Mage::getModel('customer/customer')->getEntityType();
        $this->_entityTypeId   = $this->_entityType->getEntityTypeId();
        $this->_entityTypeCode = $this->_entityType->getEntityTypeCode();
        $this->_attribute      = $this->_model->load($this->_id)->setEntityTypeId($this->_entityTypeId);
    }

    protected function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        $block = $this->getLayout()->createBlock('lgc_customerattribute/adminhtml_attribute', 'customer_attribute_grid');
        $this->getLayout()->getBlock('content')->append($block);
        $this->_title(Mage::helper('lgc_customerattribute')->__('Manage Customer Attributes'));
        $this->renderLayout();
    }

    protected function newAction()
    {
        $this->_forward('edit');
    }

    protected function editAction()
    {
        $entity = $this->_model;

        if ($this->_id) {
            $entity = Mage::getModel('customer/attribute')->load($this->_id);
        }

        $entity->getUsedInForms();

        Mage::register('entity_attribute', $entity);
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        $content = $this->getLayout()->createBlock('lgc_customerattribute/adminhtml_attribute_edit');
        $left = $this->getLayout()->createBlock('lgc_customerattribute/adminhtml_attribute_edit_tabs');
        $js = $this->getLayout()->createBlock('adminhtml/template')->setTemplate('logic/customerattribute/catalog/product/attribute/js.phtml');
        $this->_addContent($content)->_addLeft($left)->_addJs($js);
        $this->renderLayout();
    }

    protected function saveAction()
    {
        $data = $this->getRequest()->getPost();
        $helper = Mage::helper('lgc_customerattribute/form_type');
        $attribute = Mage::getModel('customer/attribute')->loadByCode($this->_entityTypeId, $this->_code);

        if ($attribute->getId() && !$this->_attribute->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lgc_customerattribute')->__('Attribute with the same code already exists'));
            $this->_redirect('*/*/edit', array('attribute_id' => $this->_id));
            return;
        }

        if ($data) {
            if ($this->_id) {
                $data['attribute_code']  = $this->_attribute->getAttributeCode();
                $data['is_user_defined'] = $this->_attribute->getIsUserDefined();
                $data['frontend_input']  = $this->_attribute->getFrontendInput();
            } else {
                $data['source_model']  = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
                $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
                $data['is_user_defined'] = 1;
            }

            if (is_null($this->_attribute->getIsUserDefined()) || $this->_attribute->getIsUserDefined() != 0) {
                $data['backend_type'] = $this->_attribute->getBackendTypeByInput($data['frontend_input']);
            }

            $data = $this->_filterPostData($data);
            $this->_attribute->addData($data);

            // Clear previous relationships
            Mage::getModel('lgc_customerattribute/store')->deleteByColumnValue('attribute_id' ,$this->_id);

            // Store the relationship of attribute and store
            if (!Mage::app()->isSingleStoreMode() && isset($data['store_view'])) {
                foreach ($data['store_view'] as $index => $id) {
                    $storeObject = Mage::getModel('lgc_customerattribute/store');
                    $storeObject->setData('attribute_id', $this->_id);
                    $storeObject->setData('store_id', $id);
                    $storeObject->save();
                }
            }

            try {
                $this->_attribute->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('Successfully saved attribute');
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->_id));
                return;
            }

            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        if ($this->_id) {
            try {
                $this->_attribute->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lgc_customerattribute')->__('The attribute has been deleted'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->_id));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lgc_customerattribute')->__('Unable to find an attribute to delete'));
        $this->_redirect('*/*/');
    }

    protected function _filterPostData($data)
    {
        if ($data) {
            foreach ($data['frontend_label'] as & $value) {
                if ($value) {
                    $value = Mage::helper('catalog')->stripTags($value);
                }
            }
        }

        return $data;
    }
}
