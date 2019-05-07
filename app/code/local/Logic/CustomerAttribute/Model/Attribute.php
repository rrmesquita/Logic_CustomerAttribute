<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Model_Attribute extends Mage_Customer_Model_Attribute
{
    public function getIsVisible($storeId = false)
    {
        if (parent::getIsVisible()) {
            $type = $this->getEntityType();
            $referenceType = Mage::getModel('customer/customer')->getEntityType();
            if ($type == $referenceType) {
                if (!$storeId) {
                    $storeId = Mage::app()->getStore()->getStoreId();
                }
                $collection = Mage::getModel('lgc_customerattribute/store')->getCollection()
                    ->addFieldToFilter('attribute_id', $this->getId())
                    ->addFieldToFilter('store_id', array($storeId, 0)); // Assume that 0 == All Store Views
                if (count($collection) > 0) {
                    return true;
                }
            }
        }

        return parent::getIsVisible();
    }
}
