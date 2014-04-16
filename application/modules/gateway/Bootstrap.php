<?php
require_once ('Zend/Application/Module/Bootstrap.php');
class Gateway_Bootstrap extends Dxcore_Application_Module_Bootstrap
{
	protected $hasFrontEndNav = false;
    protected $hasAdminNav = false;

	protected function _initResourceLoader()
    {
    	$this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
    			'basePath'  => APPLICATION_PATH . '/modules/gateway',
    			'namespace' => 'Gateway_',
    	)
    	);
    	return $this->_resourceLoader;
    }
}