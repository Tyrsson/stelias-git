<?php

/**
 * festivalorders
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';

class Festival_Model_FestivalOrders extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'festivalorders';
    protected $_sequence = true;
    protected $_primary = 'orderId';
    protected $_rowClass = 'Festival_Model_Row_Order';

    public function init(){
    	$this->log = Zend_Registry::get('log');
    }

    public function saveOrder($data)
    {
        $row = $this->fetchNew();
        $row->setFromArray($data);
        $row->order = serialize($row);
        $row->save();
    }
    public function fetchOrderDeliveryTimeByReceiptId($receiptId)
    {
    	$q = $this->select()->from($this->_name, array('orderId', 'time', 'receiptId'))->where('receiptId = ?', $receiptId);
    	return $this->fetchRow($q);
    }
    public function receiptId()
    {
    	$counter = new Admin_Model_Counters();
    	$count = $counter->fetch('receiptCount');

    	$date = Zend_Date::now();
    	$year = $date->getYear();
    	$yString = $year->toString('yyyy'). '-';
    	$str = (string) $count;
    	$l = strlen($str);

    	if($l < 6) {
    		$n = 6 - $l;
    		$padLength = $l + $n; //$padLength === 6 correct
    		$receiptId = $yString . str_pad($str, $padLength, '0', STR_PAD_LEFT);
    	}
    	elseif($l >= 6) {
    		$receiptId = $yString . $str;
    	}
    	return $receiptId;
    }

    public function fetchOrderByType($type = 'pickup', $sort = 'DESC', $perPage = 10, $page = 1, $paginated = false)
    {//TODO: This query must be fixed for the correct sorting based on time column


       	$q = $this->select()->from($this->_name)->where('type = ?', $type)->order('time '.$sort);


        // if we do not want the results paginated via the adapter then leave false or pass no argument
        if(!$paginated) {
            return $this->fetchAll($q);
        }
        // if true it will return a paginator instance
        if($paginated) {
            $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($q));
            $paginator->setItemCountPerPage($perPage);
            $paginator->setCurrentPageNumber($page);
            //Zend_Debug::dump($paginator);
            return $paginator;
        }

    }
    public function fetchObj($orderId)
    {
    	$q = $this->select()->from($this->_name)->where('orderId = ?', $orderId);
    	return $this->fetchRow($q);
    }
    public function fetch($orderId)
    {
        $q = $this->select()->from($this->_name)->where('orderId = ?', $orderId);
        $result = array();
                foreach($this->fetchRow($q)->toArray() as $k => $v) {

                    if($k === 'order') {
                        $result[$k] = unserialize($v);
                    } else  {
                        $result[$k] = $v;
                    }
                    continue;
                }
                if(!empty($result['date'])) {
                    $date = new Zend_Date($result['date'], Zend_Date::TIMESTAMP);
                    $result['date'] = $date;
                }
                return $result;
    }
}
