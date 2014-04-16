<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Ajax Action Helper
 *
 * @uses actionHelper Dxcore_Controller_Action_Helper
 */
class Dxcore_Controller_Action_Helper_AdminInfo extends Zend_Controller_Action_Helper_Abstract
{

    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    public $template;
    public $data;
    public $view;
    
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader(); 
    }
    public function preDispatch()
    {
        $this->request = $this->getRequest();
        $this->response = $this->getResponse();
        $this->params = $this->request->getParams();
        $this->controller = $this->getActionController();
        $this->module = $this->request->getModuleName();
        $this->currentAction = $this->request->getActionName();
        $this->renderer = $this->controller->getHelper('viewRenderer');
        $this->view = $this->getView();
        
        if($this->request->isXmlHttpRequest())
        {
            $this->isAjax = true;
        }

        
    }
//     public function buildWidget ()
//     {

//     }
    public function getView()
    {
        if(null !== $this->view) {
            return $this->view;
        }
        $controller = $this->getActionController();
        $view = $controller->view;
        if(!$view instanceof Zend_View_Abstract) {
            return;
        }
        return $view;
    }
//     public function direct()
//     {
//         //$this->buildWidget();
//     }
}
