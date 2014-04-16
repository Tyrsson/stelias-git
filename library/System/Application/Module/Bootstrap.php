<?php
require_once 'Zend/Application/Module/Bootstrap.php';
class System_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{
	/*
	 * @var string $module
	 * strtolower normalized module name to be used in path
	 */
    protected $module;
    /*
     * @var System_Log
     */
    protected $log;

    protected $modulePath;
    protected $iniPath;
    protected $xmlPath;
    protected $node;
    protected $adminNode;
    /*
     * @var boolean flag to include front end navigation to be overridden in class childern
     */
    protected $hasFrontEndNav = false;
    /*
     * @var boolean flag to include admin navigation to be overridden in class childern
     */
    protected $hasAdminNav = false;

    protected $router;

    public function __construct($application)
    {
        $this->log = Zend_Registry::get('log');
        parent::__construct($application);
        $this->module = strtolower($this->_moduleName);
        $this->node = $this->module;
        $this->adminNode = 'admin-'.$this->module;
        $this->modulePath = APPLICATION_PATH . '/modules/'.$this->module;
        $this->iniPath = APPLICATION_PATH . '/modules/'.$this->module.'/configs/module.ini';
        $this->xmlPath = APPLICATION_PATH . '/modules/'.$this->module.'/configs/module.xml';
        //Zend_Debug::dump(get_called_class());
    }
    protected function _initModuleConfig()
    {
    	// load ini file
    	$options = new Zend_Config_Ini($this->iniPath);
    	// Set this bootstrap options
    	$this->setOptions($options->toArray());
    }
    protected function _initModuleAutoloader()
    {
    	$this->_resourceLoader = new Zend_Application_Module_Autoloader(
    			array(
    					'basePath'  =>  $this->modulePath,
    					'namespace' => $this->_moduleName . '_',
    			)
    	);
    	return $this->_resourceLoader;
    }
    protected function _initNavigation()
    {
    	try {
        	switch($this->hasFrontEndNav) {
        		case true :
        			$navigation = new Zend_Config_Xml($this->xmlPath, $this->node);
        			//Zend_Debug::dump($navigation);
        			//if(isset($navigation->{$this->node}) && !empty($navigation->{$this->node})) {
        				$container = Zend_Registry::get ( 'Zend_Navigation' );
        				$container->addPages($navigation);
        			//}
        			break;

        		case false :

        			break;
        	}
        } catch (Exception $e) {
            $this->log->debug(__CLASS__. '::' . __METHOD__ . '::Line ' . __LINE__ . '::Error Message: ' . $e->getMessage());
        }
    }
    protected function _initAdminNavigation()
    {
        try {
        	switch($this->hasAdminNav) {
        		case true :
        			$navigation = new Zend_Config_Xml($this->xmlPath, $this->adminNode);
        			//if( isset( $navigation->{$this->adminNode} ) ) {
        				$container = Zend_Registry::get ( 'Admin_Navigation' );
        				$container->addPages($navigation);
        			//}
        			break;

        		case false :

        			break;
        	}
        } catch (Exception $e) {
            $this->log->debug(__CLASS__. '::' . __METHOD__ . '::Line ' . __LINE__ . '::Error Message: ' . $e->getMessage());
        }
    }
    protected function getRouter() {
    	$front = Zend_Controller_Front::getInstance();
    	$this->router = $front->getRouter();
    	return $this->router;
    }
}