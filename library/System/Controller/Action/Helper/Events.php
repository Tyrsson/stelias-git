<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';


/**
 * Events Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_Events extends System_Controller_Action_Helper_Widget
{
    public $view;
    public $events;
    public $sa;
    public function __construct ()
    {
        parent::__construct();
        $this->events = new Events_Model_Events();
    }
    public function preDispatch()
    {
        parent::preDispatch();
    }
    public function buildWidget()
    {
        try {
            $this->sa = $this->request->getQuery('sa', 'listAll');
            switch($this->sa) {
                case 'listAll' :
                    
                    break;
                case 'detail' :
                        die('detail');
                    break;
                    
                default:
                    throw new Zend_Controller_Action_Exception('Unknown sub action! Please contact a site admin for assistance.', 500);
                    break;
            }
            parent::renderWidget($this->data, $module = 'events');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
