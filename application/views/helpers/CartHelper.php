<?php
/**
 *
 * @author jsmith
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * CartHelper helper
 *
 * @uses viewHelper Cart_View_Helper
 */
class Zend_View_Helper_CartHelper extends Zend_View_Helper_Abstract {
	
	public $cart;
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function cartHelper() {
		
		$this->cart = new Cart_Model_Cart();
		$cartItems = $this->cart->getItems();
		
		//Zend_Debug::dump($cartItems, 'cartitems');
		
		if(null === $cartItems || count($cartItems) < 1 ) {
			return;
		} 
		elseif(count($cartItems) > 0) {
			
			$this->itemCount = count($cartItems);
			//$this->totalItems = $this->
			
			$output = '<div id="cart"><h3>Total Items ('. $this->itemCount .')</h3>';
			
			foreach ($cartItems as $item) {
				
				//$output .= '<ul>Item Name:&nbsp;' . $item->name . 
				//              '<li>Quanity:&nbsp;' . $item->qty . '</li></ul>';
				
			}
			$output .= '</div>';
			
			return '<div id="basket">' . $output . '</div>';
			
		}
	
		$this->view->itemCount = count($cartItems);
		//Zend_Debug::dump($cartItems);
		//return null;
	}
	
	/**
	 * Sets the view field
	 * 
	 * @param $view Zend_View_Interface        	
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
