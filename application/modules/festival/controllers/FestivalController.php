<?php

/**
 * FestivalController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Festival_FestivalController extends Dxcore_Controller_Action
{
    //public $context = array('addaddress' => array('ajax'), 'removeaddress' => array('ajax'));
    protected $_cmd;
    public function preDispatch()
    {
        $this->_cmd = $this->_request->getParam('cmd', 'exit');
        $this->view->cmd = $this->_cmd;
    }
    public function init()
    {
        parent::init();
        
        Zend_Controller_Action_HelperBroker::addHelper(new ZendX_JQuery_Controller_Action_Helper_AutoComplete());
        //parent::postDispatch();
        
        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('addaddress', array('html'))
             ->addActionContext('removeaddress', array('html'))->initContext();
    }
    public function addaddressAction()
    {
        //Zend_Debug::dump($this->_cmd);
        if(!$this->isAjax()) {
            throw new Zend_Controller_Action_Exception('UnSupported Request Type', 404);
        }
    }
    public function removeaddressAction()
    {
        //Zend_Debug::dump($this->_cmd);
        if(!$this->isAjax()) {
            throw new Zend_Controller_Action_Exception('UnSupported Request Type', 404);
        }
        
    }
    public function zipcodesAction()
    {
        $codes = array(
                'Birmingham' => array('', ''),
                'Vestavia' => array('', ''));
        switch ($this->isAjax()) {
            case true :
                
                break;
            case false :
                
                break;
        }
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        exit;
    }
//     public function orderingAction()
//     {
//         $this->view->widget = $this->{$this->action};
//     }
}
