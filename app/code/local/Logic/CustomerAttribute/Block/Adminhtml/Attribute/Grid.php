<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

class Logic_CustomerAttribute_Block_Adminhtml_Attribute_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('attribute_id');
        $this->setId('lgc_customerattribute_attribute_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customer/attribute')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toArray();

        $this->addColumn('attribute_id', array(
            'header' => Mage::helper('lgc_customerattribute')->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'attribute_id'
        ));

        $this->addColumn('attribute_code', array(
            'header' => Mage::helper('lgc_customerattribute')->__('Attribute Code'),
            'width'  => '200px',
            'index'  => 'attribute_code'
        ));

        $this->addColumn('frontend_label', array(
            'header' => Mage::helper('lgc_customerattribute')->__('Frontend Label'),
            'index'  => 'frontend_label'
        ));

        $this->addColumn('frontend_input', array(
            'header' => Mage::helper('lgc_customerattribute')->__('Type'),
            'width'  => '200px',
            'index'  => 'frontend_input'
        ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('lgc_customerattribute')->__('Sort Order'),
            'width'  => '100px',
            'index'  => 'sort_order'
        ));

        $this->addColumn('is_system', array(
            'header'  => Mage::helper('lgc_customerattribute')->__('System'),
            'align'   => 'center',
            'width'   => '100px',
            'index'   => 'is_system',
            'type'    => 'options',
            'options' => $yesnoSource
        ));

        $this->addColumn('is_user_defined', array(
            'header'  => Mage::helper('lgc_customerattribute')->__('User Defined'),
            'align'   => 'center',
            'width'   => '100px',
            'index'   => 'is_user_defined',
            'type'    => 'options',
            'options' => $yesnoSource
        ));

        $this->addColumn('is_required', array(
            'header'  => Mage::helper('lgc_customerattribute')->__('Required'),
            'align'   => 'center',
            'width'   => '100px',
            'index'   => 'is_required',
            'type'    => 'options',
            'options' => $yesnoSource
        ));

        $this->addColumn('is_visible', array(
            'header'  => Mage::helper('lgc_customerattribute')->__('Visible'),
            'align'   => 'center',
            'width'   => '100px',
            'index'   => 'is_visible',
            'type'    => 'options',
            'options' => $yesnoSource
        ));

        $this->addColumn('is_unique', array(
            'header'  => Mage::helper('lgc_customerattribute')->__('Unique'),
            'align'   => 'center',
            'width'   => '100px',
            'index'   => 'is_unique',
            'type'    => 'options',
            'options' => $yesnoSource
        ));

        $this->addColumn('action_edit', array(
            'header'   => Mage::helper('lgc_customerattribute')->__('Action'),
            'align'    => 'center',
            'width'    => '75px',
            'sortable' => false,
            'filter'   => false,
            'index'    => 'attribute_id',
            'type'     => 'action',
            'actions'  => array(array(
                'caption' => Mage::helper('lgc_customerattribute')->__('Edit'),
                'url'     => array('base'=> '*/*/edit'),
                'field'   => 'attribute_id'
            ))
        ));

        return parent::_prepareColumns();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('attribute_id' => $row->getId()));
    }
}
