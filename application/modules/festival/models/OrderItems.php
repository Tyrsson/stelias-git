<?php
class Festival_Model_OrderItems
{
    protected $_data = array('details' => null, 'items' => null, 'ordertotal' => null);
    public $orderTable;
    public $saveData;
    public $inputData;
    public $items = array();
    public $nonItemData = array();
    public $orderTotal = 0.00;

    public $curMenu;
    public $menu;
    public $menuTable;
    public $menuItems;
    public $menuItemsTable;


    public function __construct($inputData)
    {
        $this->orderTable = new Festival_Model_FestivalOrders();
        $this->menuItemsTable = new Festival_Model_MenuItems();
        $this->menuItems = $this->menuItemsTable->fetchCurrentMenu();

        if(is_array($inputData)) {
            $this->setInputData($inputData);
        }
        else {
            // replace with try catch
          die('data is not an array');
        }
        $this->process();

    }

    public function process()
    {
        if(!isset($this->inputData))
        {
            die('data must be set');
        }
        foreach($this->inputData as $k => $v) {

            if($v === null || empty($v))
            {
                continue;
            }

            if( strpos($k, '_') === false ) {
                // this is a none item $k
                //$this->nonItemData[$k] = $v;
                continue;
            }
            else
            {
                $parts = explode('_', $k);
                if($parts[1] === 'item') {
                    // this is item data
                    $this->items[] = array('itemId' => $parts[2], 'qty' => $v, 'item_total' => $this->getItemTotal($parts[2], $v));
                }
            }
            continue;

        }
        foreach ($this->items as $item) {
        	$this->addToTotal($item['item_total']);
        	continue;
        }
        //$order['details'] = $this->nonItemData;
        $order['items'] = $this->items;
        //$order['ordertotal'] = $this->getOrderTotal();
        //Zend_Debug::dump($order);
        $this->setData(array_intersect_key($order, $this->_data));

        //Zend_Debug::dump($this->getData());

        unset($parts);
        unset($k);
        unset($v);
        return $this->getData();
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
    /**
	 * @return the $orderTotal
	 */
	public function getOrderTotal() {
		return $this->orderTotal;
	}
	// Add item total to order total
	public function addToTotal($itemTotal)
	{
		$this->orderTotal += $itemTotal;
	}
	/**
	 * @param field_type $orderTotal
	 */
	public function setOrderTotal($orderTotal) {

		$this->orderTotal = $orderTotal;
	}

	/**
     * @return the $_data
     */
    public function getData ()
    {
        return $this->_data;
    }

	/**
     * @param field_type $_data
     */
    public function setData ($data)
    {
        $this->_data = $data;
    }

	/**
     * @return the $saveData
     */
    public function getSaveData ()
    {
        return $this->saveData;
    }

	/**
     * @param field_type $saveData
     */
    public function setSaveData ($saveData)
    {
        $this->saveData = $saveData;
    }

	/**
     * @return the $inputData
     */
    public function getInputData ()
    {
        return $this->inputData;
    }

	/**
     * @param field_type $inputData
     */
    public function setInputData ($inputData)
    {
        $this->inputData = $inputData;
    }

}