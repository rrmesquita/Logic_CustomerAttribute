<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Model_Store extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('lgc_customerattribute/store');
    }

    /**
     * Delete a set of records that contain an specific value from an specific column of a table
     *
     * @param string $column - The database column to filter
     * @param int $value - The id to filter
    */
    public function deleteByColumnValue($column, $value)
    {
        $collection = $this->getCollection()->addFieldToFilter($column, $value);
        foreach($collection as $item) {
            $item->delete();
        }
    }
}
