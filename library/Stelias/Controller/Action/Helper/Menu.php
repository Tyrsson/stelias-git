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
class Stelias_Controller_Action_Helper_Menu extends Dxcore_Controller_Action_Helper_Widget
{
    public $menuId;
    public $items;

    public function __construct()
    {
        parent::__construct();

        require_once (APPLICATION_PATH . '/modules/festival/models/MenuItems.php');
        require_once (APPLICATION_PATH . '/modules/festival/models/Menu.php');
        require_once (APPLICATION_PATH . '/modules/festival/models/FestivalOrders.php');
        $this->items = new Festival_Model_MenuItems();
    }

    public function buildWidget ()
    {
        $this->data->menuItems = $this->items->fetchCurrentMenu()->toArray();

        $types = $this->items->fetchItemTypes();
       // Zend_Debug::dump($types);
        foreach($types as $key => $value) {
        	//Zend_Debug::dump($value, '$value');
        	$this->data->types[$value['itemType']] = $this->items->fetchCurrentItemsByType($value['itemType'])->toArray();
        }
        $this->data->types = array_reverse($this->data->types, true);


//         if( $this->today->isLater($this->start) && $this->today->isEarlier($this->end) )
//         {
//             $this->data->allow = true;
//         }

        $this->renderWidget($this->data, 'festival');
    }

}
