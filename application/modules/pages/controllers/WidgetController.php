<?php

/**
 * WidgetController
 * 
 * @author
 * @version 
 */
require_once 'Dxcore/Controller/Action.php';

class WidgetController extends Dxcore_Controller_Action
{
	public function init() {
		// TODO: Auto-generated method stub
		parent::init();
	}
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated WidgetController::indexAction() default action
    }
    public function menuAction() 
    {
        $this->items = new Festival_Model_MenuItems();
        
        $this->data->menuItems = $this->items->fetchCurrentMenu()->toArray();
        
        $types = $this->items->fetchItemTypes();
        // Zend_Debug::dump($types);
        foreach($types as $key => $value) {
            //Zend_Debug::dump($value, '$value');
            $this->data->types[$value['itemType']] = $this->items->fetchCurrentItemsByType($value['itemType'])->toArray();
        }
        $this->data->types = array_reverse($this->data->types, true);
    }
    public function orderingAction() 
    {
        
    }
}
