<?php

class Clockworkgeek_Extrarestful_Model_Api2_Review_Rest_Customer_V1
extends Clockworkgeek_Extrarestful_Model_Api2_Review
{

    /**
     * Hides ID and Status for customers other than the calling one
     *
     * @see Clockworkgeek_Extrarestful_Model_Api2_Review::_retrieveCollection()
     */
    protected function _retrieveCollection()
    {
        $reviews = $this->_getReviews()->load();
        /* @var $review Mage_Review_Model_Review */
        foreach ($reviews as $review) {
            if ($review->getCustomerId() != $this->getApiUser()->getUserId()) {
                $review->unsCustomerId()->unsStatus();
            }
        }
        $collection = $reviews->toArray();
        return (array) @$collection['items'];
    }

    /**
     * A collection of approved review objects
     *
     * Customers do not get to see pending or rejected reviews.
     *
     * @see Clockworkgeek_Extrarestful_Model_Api2_Review::_getReviews()
     */
    protected function _getReviews()
    {
        if (!$this->_getStore()->getIsActive()) {
            $this->_critical('Requested store is invalid', Mage_Api2_Model_Server::HTTP_BAD_REQUEST);
        }

        $reviews = parent::_getReviews();
        $reviews->join(
            array('statuses' => $reviews->getTable('review/review_status')),
            'main_table.status_id=statuses.status_id',
            array('status' => 'status_code'));

        // status is approved OR customer is current user
        $reviews->addFieldToFilter(array(
            'main_table.status_id',
            'detail.customer_id'
        ), array(
            Mage_Review_Model_Review::STATUS_APPROVED,
            $this->getApiUser()->getUserId()
        ));
        return $reviews;
    }

    protected function _create($data)
    {
        $data['customer_id'] = $this->getApiUser()->getUserId();
        return parent::_create($data);
    }
}
