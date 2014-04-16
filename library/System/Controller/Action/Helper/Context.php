<?php
/**

 */
class System_Controller_Action_Helper_Context extends Zend_Controller_Action_Helper_Abstract
{
    protected $_context;
    public $view;
    protected $_request;
    public $c;
    public $a;
    public $m;
    public $pageUrl;
    public $renderer;
    //public $suffix;
    //public $template;
    //public $widgetName;
    public $isAjax = false;
    public $params;
    public $get;
    public $post;
    public $skin;

    public function __construct ()
    {
    	// TODO Auto-generated Constructor
    	$this->pluginLoader = new Zend_Loader_PluginLoader();

    	if(Zend_Registry::isRegistered('appSettings'))
    	{
    		$this->appSettings = Zend_Registry::get('appSettings');
    	}
    }
    public function preDispatch()
    {
    	$this->_request = $this->getRequest();
    	$this->view = $this->getView();
    	if($this->isAjax())
    	{
    		$this->isAjax = true;
    	}
     	$this->c = $this->_actionController;
     	$this->m = $this->_request->getModuleName();
     	$this->a = $this->_request->getActionName();

     	$this->renderer = $this->c->getHelper('viewRenderer');
     	$this->view = $this->getView();

     	// the layout needs this
     	$skin = new Admin_Model_SkinSettings();
     	$row = $skin->fetchCurrent();
     	$this->view->skin = $row;

    }
    public function init()
    {
    	//$this->view = $this->getView();


    }
    public function direct()
    {
        //return $this->initContext();
    }
    public function getView()
    {
    	if(null !== $this->view) {
    		return $this->view;
    	}
    	//$controller = $this->getActionController();

    	$view = $this->_actionController->view;
    	if(!$view instanceof Zend_View_Abstract) {
    		return;
    	}
    	return $view;
    }
    public function isAjax() {
    	return $this->_request->isXmlHttpRequest();
    }
    /**
     * Overloading
     *
     * Overloading to provide dynamic methods
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     * @throws Zend_Controller_Action_Exception for invalid methods
     */
    public function __call($method, $args)
    {
    	$method = strtolower($method);
    	if ('isAjax' == $method) {
    		return call_user_func_array(array($this, 'isAjax'), $args);
    	}

    	require_once 'Zend/Controller/Action/Exception.php';
    	throw new Zend_Controller_Action_Exception(sprintf('Invalid method "%s" called on context', $method));
    }
}