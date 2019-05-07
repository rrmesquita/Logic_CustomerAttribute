<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Model_Customer_Group extends Mage_Core_Model_Abstract
{
    public function getCustomerGroupsArray()
    {
        $array = array();
        $collection = Mage::getModel('customer/group')->getCollection();
        $index = 0;

        foreach($collection as $group) {
            $array[$index] = array(
                'value' => $group->getData('customer_group_id'),
                'label' => $group->getData('customer_group_code')
            );
            $index++;
        }

        return $array;
    }
}
