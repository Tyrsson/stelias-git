<?php
/**
 * @author Joey Smith
 * @version 0.9.1
 * @package Dxcore
 */
require_once ('Zend/Application/Module/Bootstrap.php');
class User_Bootstrap extends Zend_Application_Module_Bootstrap
{

    /**
     * Initialize module resources
     *
     * @return mixed registry items
     */
    protected function _initUserModuleAutoloader() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);

        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
        																	 'basePath'  => APPLICATION_PATH . '/modules/user',
        																	 'namespace' => 'User_',
        																	 )
        																);
        $this->_resourceLoader->addResourceTypes(array(
        // DO NOT REMOVE
        'acl' => array(
        			'path' => 'acl/',
                    'namespace' => 'Acl'
        ),
        'plugins' => array(
        			'path' => 'controllers/plugins',
                    'namespace' => 'Controller_Plugin')
        )
        );
        return $this->_resourceLoader;
    }

	/**
	 * Test to show returned values will go into container
	 * then they can be accessed by this resource method's name
	 * without the '_init' prefix that is (moduledata)
	 */
    protected function _initUserData()
    {
    	//$data = array(1,2,3,4,5,6,7,8,9,0);
    	//return $data;
    }

	/**
     * Init config settings and resoure for this module
     *
     */
    protected function _initUserConfig()
    {
    	// load ini file
    	$iniOptions = new Zend_Config_Ini(dirname(__FILE__) . '/configs/user.ini');

    	// Set this bootstrap options
    	$this->setOptions($iniOptions->toArray());

    }

    protected function _initAcl() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
        //This sets the bootstraps the Acl Plugin
        $acl = new User_Controller_Plugin_Acl();
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new User_Controller_Plugin_Acl($acl));
    }
    protected function _initUserNav()
    {
    	$userNavConfig = new Zend_Config_Xml(APPLICATION_PATH . '/modules/user/configs/navigation.xml', 'usernav');
    	$container = Zend_Registry::get ( 'Zend_Navigation' );
    	$container->addPages($userNavConfig);
    }
    protected function _initAdminUserNav()
    {
    	$adminUserNavConfig = new Zend_Config_Xml(APPLICATION_PATH . '/modules/user/configs/navigation.xml', 'adminusernav');
    	$adminContainer = Zend_Registry::get ( 'Admin_Navigation' );
    	$adminContainer->addPages($adminUserNavConfig);
    }
    protected function _initReCaptcha()
    {
        //$this->options = $this->getOptions();
        //Zend_Debug::dump($this->options, 'boostrap');
        //Zend_Registry::set('user.recaptcha', $this->options['recaptcha']);
    }
//     protected function _initLoginWidget() {
//         Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_LoginWidget());
//     }

}