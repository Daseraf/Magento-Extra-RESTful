<?php

class Clockworkgeek_Extrarestful_Model_Api2_Rewrite extends Clockworkgeek_Extrarestful_Model_Api2_Abstract
{

    protected function _getCollection()
    {
        /* @var $rewrites Mage_Core_Model_Resource_Url_Rewrite_Collection */
        $rewrites = parent::_getCollection();
        $rewrites->addFieldToFilter('is_system', 0);
        if (! is_null($storeId = $this->getRequest()->getParam('store'))) {
            $rewrites->addStoreFilter($storeId);
        }

        return $rewrites;
    }
}
