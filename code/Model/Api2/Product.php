<?php

/**
 * An almost replacement for <code>/api/rest/products</code>
 *
 * Some important differences are:
 * <li>Dropdown and multiselect type attributes are replaced with their localised values
 * <li>Product URLs are correct for the specified store
 * <li>Flat tables are used for performance reasons
 * <li>No <code>buy_now_url</code> because it's not RESTful
 * <li>Carefully avoids sessions
 * <li>All inherited benefits like better pagination
 */
class Clockworkgeek_Extrarestful_Model_Api2_Product extends Clockworkgeek_Extrarestful_Model_Api2_Abstract
{

    public function getFilter()
    {
        if (!$this->_filter) {
            $source = $this->_getSubModel('product', $this->getRequest()->getParams());
            $filter = $source->getFilter();
            $this->setFilter($filter);
        }
        return $this->_filter;
    }

    public function setApiUser(Mage_Api2_Model_Auth_User_Abstract $apiUser)
    {
        parent::setApiUser($apiUser);
        // override admin settings for actual tax display so we can get both with & without tax values
        $this->_getStore()->setConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE, Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH);
        return $this;
    }

    protected function _loadModel()
    {
        $product = parent::_loadModel();
        $this->_prepareProduct($product);
        return $product;
    }

    protected function _getCollection()
    {
        /** @var $products Mage_Catalog_Model_Resource_Product_Collection */
        $products = parent::_getCollection();
        $products->addStoreFilter($this->_getStore()->getId())
            ->addAttributeToSelect($this->getFilter()->getAttributesToInclude());

        if (in_array('image_url', $this->getFilter()->getAttributesToInclude())) {
            // addAttributeToSelect does not work with flat tables
            // must use joinAttribute which also works fine with EAV tables
            $products->joinAttribute('image', 'catalog_product/image', 'entity_id');
        }
        return $products;
    }

    protected function _loadCollection(Varien_Data_Collection_Db $products)
    {
        parent::_loadCollection($products);

        foreach ($products as $product) {
            $this->_prepareProduct($product);
        }
    }

    protected function _prepareProduct(Mage_Catalog_Model_Product $product)
    {
        $include = $this->getFilter()->getAttributesToInclude();
        $storeId = $this->_getStore()->getId();
        if (!$product->hasStoreId()) {
            $product->setStoreId($storeId);
        }

        // replace attribute value IDs with frontend legible values
        /** @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
        foreach ($product->getAttributes() as $attribute) {
            $code = $attribute->getAttributeCode();
            if (!$product->hasData($code) || !in_array($code, $include)) {
                continue;
            }
            if ($product->getData($code.'_value')) {
                $product->setData($code, $product->getData($code.'_value'));
            }
            elseif ($attribute->usesSource() && $attribute->getIsVisibleOnFront()) {
                $product->setData($code, $product->getAttributeText($code));
            }
        }

        // do not have buy_now_url because it depends on session and form_key
        // neither apply to a proper API, use a cart endpoint instead
        if (in_array('has_custom_options', $include)) {
            $product->setHasCustomOptions(count($product->getOptions()) > 0);
        }
        if (in_array('image_url', $include)) {
            $product->setImageUrl((string) Mage::helper('catalog/image')->init($product, 'image'));
        }
        if (in_array('is_in_stock', $include) && $product->getStockItem()) {
            $product->setIsInStock((bool) $product->getStockItem()->getIsInStock());
        }
        if (in_array('is_saleable', $include)) {
            $product->setIsSaleable((bool) $product->getIsSalable());
        }
        if (in_array('regular_price_with_tax', $include)) {
            $product->setRegularPriceWithTax($this->_getPrice($product, $product->getPrice(), true));
        }
        if (in_array('regular_price_without_tax', $include)) {
            $product->setRegularPriceWithoutTax($this->_getPrice($product, $product->getPrice(), false));
        }
        if (in_array('required_options', $include)) {
            $product->setRequiredOptions((bool) $product->getRequiredOptions());
        }
        if (in_array('tier_price', $include)) {
            $product->setTierPrice(array_map(function($tier) use ($product) {
                return array(
                    'qty' => @$tier['price_qty'],
                    'price_with_tax' => $this->_getPrice($product, @$tier['price'], true),
                    'price_without_tax' => $this->_getPrice($product, @$tier['price'], false)
                );
            }, (array) $product->getData('tier_price')));
        }
        if (in_array('total_reviews_count', $include)) {
            $product->setTotalReviewsCount((int)
                Mage::getModel('review/review')->getTotalReviews($product->getId(), true, $storeId));
        }
        if (in_array('url', $include)) {
            $product->setUrl($product->getUrlModel()->getUrl($product, array(
                // prevent accidentally starting session for SID check
                '_nosid' => true
            )));
        }
    }

    /**
     * Calculate total price where tax could be influenced by the product or customer
     *
     * @param Mage_Catalog_Model_Product $product
     * @param float $price
     * @param bool $withTax
     * @return float
     */
    protected function _getPrice(Mage_Catalog_Model_Product $product, $price, $withTax)
    {
        // false for address/customer group means do not use
        // null would mean look it up using session, which is not applicable here
        return Mage::helper('tax')->getPrice($product, $price, $withTax, false, false, false);
    }
}
