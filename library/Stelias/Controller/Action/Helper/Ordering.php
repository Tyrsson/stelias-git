<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Festival Action Helper
 *
 * @uses actionHelper Stelias_Controller_Action_Helper
 */
class Stelias_Controller_Action_Helper_Ordering extends Dxcore_Controller_Action_Helper_Widget
{
    public $menuId;
    public $orderHandler;
    public $orderTotal = 0.00;
    public $appSettings;
    public $startHour;
    public $endHour;
    public $startDate;
    public $endDate;
    public $start;
    public $end;
    public $today;
    public $log;
    public $deliveryTime;
    public $meridiem;
    private $receiptId;
    private $counter;

    public function __construct()
    {
        parent::__construct();
        $this->log = Zend_Registry::get('log');
        require_once (APPLICATION_PATH . '/modules/festival/models/MenuItems.php');
        require_once (APPLICATION_PATH . '/modules/festival/models/Menu.php');
        require_once (APPLICATION_PATH . '/modules/festival/models/Row/Order.php');
        require_once (APPLICATION_PATH . '/modules/festival/models/FestivalOrders.php');
        $this->data->allow = false;
        $this->appSettings = Zend_Registry::get('appSettings');
        $this->setStart();
        $this->setEnd();
        $this->today = Zend_Date::now();
        $this->items = new Festival_Model_MenuItems();
        $this->menu = new Festival_Model_Menu();
        $menu = $this->menu->fetchCurrent();
        //Zend_Debug::dump($menu);
        $this->menuId = (int)$menu->menuId;

        $this->orderTable = new Festival_Model_FestivalOrders();
        $this->orders = $this->orderTable->fetchAll();

        $this->menuItems = $this->items->fetchCurrentMenu();
        //$this->log->debug($this->orderTable->receiptId());
        $this->counter = new Admin_Model_Counters();
    }

    public function setStart()
    {
        $startDate = $this->appSettings->orderingStartDay;
        $startHour = $this->appSettings->orderingStartHour;

        $parts = explode('/', $startDate);
        $this->start = new Zend_Date(array(
                'year' => $parts[2],
                'month' => $parts[0],
                'day' => $parts[1],
                'hour' => $startHour // this needs a default
        ));
        //Zend_Debug::dump($this->start, 'start time');
    }
    public function setEnd()
    {
        $endHour = $this->appSettings->orderingEndHour;
        $endDate = $this->appSettings->orderingEndDay;

        $parts = explode('/', $endDate);
        $this->end = new Zend_Date(array(
                'year' => $parts[2],
                'month' => $parts[0],
                'day' => $parts[1],
                'hour' => $endHour // this needs a default
        ));
        //Zend_Debug::dump($this->end);
    }
    public function handleSubmit($orderData)
    {




        //Zend_Debug::dump($orderData,'raw form data');
        $this->setDeliveryTimeObj($orderData['day'], $orderData['time']);
        $this->orderItems = $this->processOrderData($orderData);
        $row = $this->orderTable->fetchNew();
        $row->setFromArray($orderData);

        $row->time = $orderData['day'] . ' ' . $orderData['time'];
        $this->log->debug($row->time);

       // $this->log->debug(Zend_Debug::dump($this->deliveryTime->toString()));
        $row->pickup_delivery_time = $this->deliveryTime->getTimeStamp();

        //$row->meridiem = $this->getMeridiem();
        $row->total = $this->getTotal();
        $date = Zend_Date::now();
        $row->date = $date->getTimestamp();

        // this is to prevent the OrderMailer from having to unserialize the order items
        $row->order = $this->orderItems;
        $this->counter->add('receiptCount'); // increment the order count by ++
        $row->receiptId = $this->orderTable->receiptId(); // get a receiptId from the model after the counter has been ++'ed



        // prepare the order for confirmation mail
        $receiptData = clone($row);

        $row->order = serialize($this->orderItems);

        $orderId = $row->save();
        if($orderId > 0) {

        	$mailService = new Festival_Service_OrderMailer(new Zend_Mail(), null, $receiptData, $orderId, 'Festival_Model_MenuItems');
        }
        else {

        }
    }

    /**
	 * @return the $deliveryTime
	 */
	public function getDeliveryTime() {
		return $this->deliveryTime;
	}

	/**
	 * @param field_type $deliveryTime
	 */
	public function setDeliveryTime($deliveryTime) {
		$this->deliveryTime = $deliveryTime;
	}

	public function setDeliveryTimeObj($day, $hour) {
		//America/Indiana/Tell_City
    	$dParts = explode('/', $day);
    	$day = new Zend_Date(array('year' => $dParts[0], 'month' => $dParts[1], 'day' => $dParts[2]),  'en_US');
    	$day->setTimezone('America/Indiana/Tell_City');

    	// debug
    	//$this->log->debug($day->toString());


    	$parts = explode(" ", $hour);
    	//$this->setMeridiem($parts[1]);
//     	if($parts[1] === 'PM' || $parts === 'pm') {
//     		$day->add('12', Zend_Date::HOUR_AM);
//     	}


    	$hParts = explode(':', $parts[0]);
    	//$this->log->debug($day->toString());
    	$hr = $hParts[0];
    	$min = $hParts[1];

    	$day->setHour($hr);
    	$day->setMinute($min);
    	// debug
    	$this->log->debug($day->toString());

    	$this->setDeliveryTime($day);

    }
    public function processOrderData($postData)
    {
    	$items = array();
    	if(!isset($postData))
    	{
    		die('data must be set');
    	}
    	foreach($postData as $k => $v) {

            if( strpos($k, '_') !== false ) {
    		    $parts = explode('_', $k);
    			if($parts[1] === 'item' && !empty($v) && $v != 0) {
    				// this is item data
    				$items[] = array('itemId' => $parts[2], 'name' => $this->getItemName($parts[2]), 'qty' => $v, 'item_total' => $this->getItemTotal($parts[2], $v));
    			}
    			continue;
    		}
    	}
    	foreach ($items as $item) {
    		$this->addToTotal($item['item_total']);
    		continue;
    	}
    	unset($parts);
    	unset($k);
    	unset($v);
    	return $items;
    }
    public function getItemName($id)
    {
    	$itemCount = count($this->menuItems->toArray());
    	foreach($this->menuItems->toArray() as $item) {

    		if( $item['id'] == $id ) {
    			return $item['itemName'];
    		}
    		else {
    			//return null;
    		}
    	}
    }
    public function getTotal()
    {
    	return $this->orderTotal;
    }
    public function addToTotal($itemTotal)
    {
    	(float)$this->orderTotal += $itemTotal;
    }
    public function getItemTotal($itemId, $qty)
    {
    	$itemCount = count($this->menuItems->toArray());
    	foreach($this->menuItems->toArray() as $item) {

    		if( $item['id'] == $itemId ) {
    			$itemTotal = $qty * (float)$item['price'];
    			return $itemTotal;
    		}
    		else {
    			//return null;
    		}
    	}
    }
    public function emailOrder() {

    }

    /**
	 * @return the $meridiem
	 */
	public function getMeridiem() {
		return $this->meridiem;
	}

	/**
	 * @param field_type $meridiem
	 */
	public function setMeridiem($meridiem) {
		$this->meridiem = $meridiem;
	}

	public function buildWidget ()
    {
    	switch(true) {
    		case ( $this->isAjax && $this->request->isPost() ) :
    		    
    			$this->handleSubmit($this->request->getPost());
    			break;
    		case ( $this->isAjax && $this->request->isGet()) :
    		    
    		    $this->data->menuItems = $this->items->fetchItems($this->menuId);
    		    $this->orders = $this->orderTable->fetchAll();
    		    
    			break;
    	}
        switch($this->request->isPost()) {
            case true :
               // $this->handleSubmit($this->request->getPost());
                break;
            case false :
            default:
                //$this->data->menuItems = $this->items->fetchItems($this->menuId);
                //$this->orders = $this->orderTable->fetchAll();
//                 foreach($this->orders as $order) {
//                 	//Zend_Debug::dump($order);
//                 	//Zend_Debug::dump(unserialize($order->order));
//                 }
                break;
        }
//         if( $this->today->isLater($this->start) && $this->today->isEarlier($this->end) )
//         {
            $this->data->allow = true;
        //}
        $this->renderWidget($this->data, 'festival');
    }

}
