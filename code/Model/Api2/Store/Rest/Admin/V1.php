<?php

class Clockworkgeek_Extrarestful_Model_Api2_Store_Rest_Admin_V1
extends Clockworkgeek_Extrarestful_Model_Api2_Store
{

    protected function _retrieveCollection()
    {
        $stores = array();
        foreach (Mage::app()->getStores() as $id => $store) {
            $stores[$id] = $store->getData();
        }
        return $stores;
    }
}
