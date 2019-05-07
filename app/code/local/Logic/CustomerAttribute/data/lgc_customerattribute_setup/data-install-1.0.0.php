<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

/*
 * As the "store_view" data is not available on native customer attributes,
 * this data install script make sure that every customer attribute has
 * at least the assigned value of "All Store Views"
 */
$collection = Mage::getModel('customer/attribute')->getCollection();
foreach($collection as $attribute) {
    $data = array('attribute_id' => $attribute->getId(), 'store_id' => 0);
    Mage::getModel('lgc_customerattribute/store')->setData($data)->save();
}
