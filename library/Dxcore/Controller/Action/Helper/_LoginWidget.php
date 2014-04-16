<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * UserTools Action Helper
 *
 * @uses actionHelper Dxcore_Controller_Action_Helper
 */
class Dxcore_Controller_Action_Helper_LoginWidget extends Dxcore_Controller_Action_Helper_Widget implements Dxcore_Controller_Action_Helper_WidgetInterface
{
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }
    public function preDispatch()
    {
        parent::preDispatch();
        //Zend_Debug::dump($this->appSettings, 'user tools widget'); 
    }
/* (non-PHPdoc)
     * @see Dxcore_Controller_Action_Helper_Widget::buildWidget()
     */
    public function buildWidget ()
    {
        // TODO Auto-generated method stub
    }
//     public function renderWidget()
//     {
        
//     }

    
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
