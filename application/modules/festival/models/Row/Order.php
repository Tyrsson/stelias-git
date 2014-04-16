<?php
class Festival_Model_Row_Order extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'Festival_Model_FestivalOrders';

    protected $itemsObj;
    public function init() {
    	//$this->itemsObj = new Festival_Model_OrderItems();
    }
    public function getOrderItems()
    {
    	return unserialize($this->_data['order']);
    }
    public function getDeliveryTime() {
        return new Zend_Date($this->_data['pickup_delivery_time'], Zend_Date::TIMESTAMP);
    }
}