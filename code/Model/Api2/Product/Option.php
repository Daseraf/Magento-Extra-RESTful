<?php

class Clockworkgeek_Extrarestful_Model_Api2_Product_Option extends Clockworkgeek_Extrarestful_Model_Api2_Abstract
{

    protected function _getCollection()
    {
        $productId = $this->getRequest()->getParam('product');
        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product');
        $product->setStoreId($this->_getStore()->getId());
        $product->load($productId, array('entity_id'));
        if ($productId != $product->getId()) {
            $this->_critical(self::RESOURCE_NOT_FOUND);
        }
        return $this->getWorkingModel()->getProductOptionCollection($product);
    }

    protected function _loadCollection(Varien_Data_Collection_Db $options)
    {
        parent::_loadCollection($options);

        if (in_array('values', $this->getFilter()->getAttributesToInclude())) {
            $options->addValuesToResult($this->_getStore()->getId());
            /** @var $option Mage_Catalog_Model_Product_Option */
            foreach ($options as $option) {
                $values = array();
                /** @var $value Mage_Catalog_Model_Product_Option_Value */
                foreach ($option->getValues() as $value) {
                    $values[] = $value->toArray(array(
                        'price',
                        'price_type',
                        'sku',
                        'sort_order',
                        'title'
                    )) + array(
                        'value' => $value->getId()
                    );
                }
                // setData does not affect private member variables but will be exported by Varien_Object::toArray
                $option->setData('values', $values);
            }
        }
    }
}
