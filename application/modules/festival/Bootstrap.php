<?php
require_once ('Zend/Application/Module/Bootstrap.php');
class Festival_Bootstrap extends Dxcore_Application_Module_Bootstrap
{
	protected $hasFrontEndNav = false;
    protected $hasAdminNav = true;

	protected function _initResourceLoader()
    {
    	$this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
    			'basePath'  => APPLICATION_PATH . '/modules/festival',
    			'namespace' => 'Festival_',
    	)     
    	);
    	return $this->_resourceLoader;
    }
}