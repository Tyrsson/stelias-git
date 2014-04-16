<?php

/**
 * AdminOrdersController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';
class Festival_AdminOrdersController extends Dxcore_Controller_AdminAction {

	public $orders;
	public $type;
	public $page;
	public $apiOrigin;
	public $apiDestination;

	public function preDispatch()
	{
	    $this->type = $this->_request->getParam('type', 'pickup');
	    $this->page = $this->_request->getParam('page', 1);

	}
	public function init(){
		parent::init();

		$ajax = $this->_helper->getHelper('AjaxContext');
		$ajax->addActionContext('display', array('html'))->initContext();

		$this->orders = new Festival_Model_FestivalOrders();

	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated AdminOrdersController::indexAction() default action
	}
	public function listAction()
	{
	    $form = new Festival_Form_ChangeSort();

	    switch($this->isAjax()) {
	        case true :




	            break;
	        case false :
	        default:


	            $this->view->type = $this->type;
	            $this->view->page = $this->page;
	            $this->view->orders = $this->orders->fetchOrderByType($this->type, 'ASC', (isset($this->appSettings->ordersPerPage) && !empty($this->appSettings->ordersPerPage)) ? $this->appSettings->ordersPerPage : 10, $this->page, true);

	            break;
	    }
	    $form->populate(array('type' => $this->type));
	    $this->view->form = $form;
	}
	public function displayAction()
	{
		if(isset($this->_request->id)) {
			$orderId = $this->_request->id;
		}

		$order = $this->orders->fetch($orderId);

	    switch($this->isAjax()) {
	        case true :

	        	$status = $this->getParam('status');
	        	$order = $this->orders->fetchObj($orderId);
	        	$order->completed = $status;

	        	if($order->completed == 1) {
	        		$status = 'Completed';
	        	}
	        	else {
	        		$status = 'Incomplete';
	        	}
	        	$order->save();

	        	$this->view->status = $status;

	            break;
	        case false :



	        	break;
	        default:

	            break;
	    }
	    $this->view->order = $order;
	}
}
