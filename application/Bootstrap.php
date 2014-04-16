<?php
/**
 * @author Joey Smith
 * @version 0.1
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Init a global MySQL connection for all calls to the DB
     */
    protected $db;
    protected $sessionConfig;
    protected $appSettings;
    protected $_logger;
    protected $_config;
    protected $_cache;

    protected function _initConfig() {
        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        defined('DEV') || define('DEV', 'development');
        defined('PROD') || define('PROD', 'production');
    }

    protected function _initCaching () {

        $classFileIncCache = APPLICATION_PATH . '/data/pluginLoaderCache.php';
        if (file_exists($classFileIncCache)) {
            include_once $classFileIncCache;
        }
        if ($this->_config->params->enablePluginLoaderCache) {
            Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        }

        $this->bootstrap('cachemanager');
        $resource = $this->getPluginResource('cachemanager');
        $this->_cache = $resource->getCacheManager();
        Zend_Registry::set('cache', $this->_cache);

    }
    protected function _initMysql() {
        $options = array(
        	'host' => '127.0.0.1',
//             'username' => 'steliaso_ncmsusr',
//             'password' => 'JTEts_5BfiF1',
//             'dbname' => 'steliaso_newcms'
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'stelias'
        );
        $db = Zend_Db::factory('PDO_MYSQL', $options);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        //$this->bootstrap('db');
//         switch (APPLICATION_ENV) {

//             case 'development' :
//                 $profiler = new Zend_Db_Profiler_Firebug('Aurora Queries');
//                 $profiler->setEnabled(true);
//                 $this->getPluginResource('db')->getDbAdapter()->setProfiler($profiler);
//                 break;

//             case 'production' :
//                 Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_cache->getCache('cache'));
//                 break;
//         }
    }
    /**
     * Configure the default modules autoloading, here we first create
     * a new module autoloader specifiying the base path and namespace
     * for our default module. This will automatically add the default
     * resource types for us. We also add two custom resources for Services
     * and Model Resources.
     */
	protected function _initAdminModuleAutoloader() {
            //$this->_logger->info('Bootstrap ' . __METHOD__);
        	$this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
        			'basePath'  => APPLICATION_PATH . '/modules/admin',
        			'namespace' => 'Admin_',
        	)
        	);
        	return $this->_resourceLoader;
    }
    protected function _initSearchModuleAutoloader() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH . '/modules/search',
                'namespace' => 'Search_',
        )
        );
        return $this->_resourceLoader;
    }
    protected function _initPagesModuleAutoloader() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH . '/modules/pages',
                'namespace' => 'Pages_',
        )
        );
        return $this->_resourceLoader;
    }
    protected function _initMediaModuleAutoloader() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);

        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH . '/modules/media',
                'namespace' => 'Media_',
        )
        );
        return $this->_resourceLoader;
    }
    protected function _initApplicationSettings()
    {
    	/* Usage **
    	 $test = array('blah' => 'blah');
    	$settings = new Admin_Settings_Settings($test);
    	$blah = 'blah';
    	$settings->__set('test', $blah);
    	*/
    	$appSettings = Admin_Model_SettingsGateway::getInstance();
    	$this->appSettings = $appSettings;
    	Zend_Registry::set('appSettings', $appSettings);
    	return $appSettings;
    }

    /**
     * Setup the logging
     */
    protected function _initLogging() {
        // table column mapping array
        $columnMapping = array(
        //'userId' => 'userId',
        //'userName' => 'userName',
        //'fileId'  => 'fileId',
        'timeStamp' => 'timeStamp',
        'priorityName' =>'priorityName',
        'priority' => 'priority',
        'message' => 'message');

        $this->bootstrap('frontController');
        $this->_logger = new System_Log();

        switch($this->appSettings->debugMode) {
            case true :
                $productionFilter = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
                break;
            case false :
                $productionFilter = new Zend_Log_Filter_Priority(Zend_Log::WARN);
                break;
            default:
                $productionFilter = new Zend_Log_Filter_Priority(Zend_Log::WARN);
                break;
        }
        switch(APPLICATION_ENV) {
            case 'production' :
                    $writer = new Zend_Log_Writer_Db(Zend_Db_Table_Abstract::getDefaultAdapter(), 'log', $columnMapping);
                    $writer->addFilter($productionFilter);
                break;
            case 'development' :
                    $writer = new Zend_Log_Writer_Firebug();
                break;
        }
        $this->_logger->addWriter($writer);

        Zend_Registry::set('log', $this->_logger);
    }
    protected function _initSession() {
        //if('production' == $this->getEnvironment()) {
	        //$this->_logger->info('Bootstrap ' . __METHOD__);
	        $this->sessionConfig = array(
	        'name'           => 'session',
	        'primary'        => 'id',
	        'modifiedColumn' => 'modified',
	        'dataColumn'     => 'data',
	        'lifetimeColumn' => 'lifetime'
	        );
	        Zend_Session::setOptions(array(
	        							//'cookie_secure' => true, //only if using SSL
	        							//'use_only_cookies' => true,
	        							'gc_maxlifetime' => ( isset($this->appSettings->sessionLength) ) ? (int) $this->appSettings->sessionLength : 15 * 60, // use setting or fall back to 15 minutes
	        							'cookie_httponly' => true
	        							)
	        						);

	        Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($this->sessionConfig));
	        Zend_Session::start();
       // }
        //Zend_Session::regenerateId();
    }
    protected function _initSkin() {
        //Zend_Debug::dump($this->getAppNamespace());
        //$this->_logger->info('Bootstrap ' . __METHOD__);

        // make sure these are initialized for use
        $this->bootstrap('layout');
        $this->bootstrap('view');
        $this->bootstrap('useragent');

        $layout = $this->getResource('layout'); // get the layout object
        $view = $this->getResource('view'); //get the view object
        $ua = $this->getResource('useragent'); // get the user agent object
        // we will need this to set the action script path based on skin and mobile
        //$viewRenderer = new Dxcore_Controller_Action_Helper_ViewRenderer();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        //$viewRenderer->setSkinName('blah'); call only on the Dxcore renderer
        //Zend_Debug::dump($viewRenderer);
        // grab our skin model so we can determine which is the current
        $skin = new Admin_Model_Skins();
        $row = $skin->fetchCurrent();
        $this->skinName = $row->skinName;
        $isDefault = false;
        if($this->skinName === 'default') {
            $isDefault = true;
        }
        defined('SKIN_NAME') || define('SKIN_NAME', $this->skinName);
        $layout->setLayoutPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->skinName . DIRECTORY_SEPARATOR);
//         $layoutBasePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR;
//         $defaultSkinLayoutPath = $layoutBasePath . 'default' . DIRECTORY_SEPARATOR;
//         $defaultMobileLayoutPath = $defaultSkinLayoutPath . 'mobile' . DIRECTORY_SEPARATOR;

//         $currentSkinLayoutPath = $layoutBasePath . $this->skinName . DIRECTORY_SEPARATOR;
//         $currentSkinMobileLayoutPath = $currentSkinLayoutPath . 'mobile' . DIRECTORY_SEPARATOR;

        $device = $ua->getDevice();

        if($device instanceof Zend_Http_UserAgent_Mobile) {
            defined('IS_MOBILE') || define('IS_MOBILE', true);

        } else {
            defined('IS_MOBILE') || define('IS_MOBILE', false);
        }

        Zend_Registry::set('browserInfo', $device->getAllFeatures());
        Zend_Registry::set('deviceWidth', $device->getPhysicalScreenWidth());
        Zend_Registry::set('deviceHeight', $device->getPhysicalScreenHeight());



//         switch(IS_MOBILE) {
//             // mobile device
//             case true :
//                 if($this->appSettings->enableMobileSupport)
//                 {

//                     //$layout->setLayoutPath($defaultMobileLayoutPath);

//                     if(is_dir($defaultMobileLayoutPath))
//                     {
//                         $layout->setLayoutPath($defaultMobileLayoutPath);
//                     }
//                     else
//                     {
//                         throw new Zend_Application_Resource_Exception('Default mobile skin folder is missing!');
//                     }
//                 }
//                 else
//                 {
//                     // if mobile support is not enabled we fall back to the desktop if its the current
//                     if($isDefault)
//                     {
//                         $layout->setLayoutPath($defaultSkinLayoutPath);
//                     }
//                 }
//                 break;
//                 // desktop
//             case false :
//                 if($this->skinName === 'default') {

//                     if(is_dir($defaultSkinLayoutPath)) {
//                         $layout->setLayoutPath($defaultSkinLayoutPath);
//                     }
//                  } else {
//                     $layout->setLayoutPath($layoutBasePath . DIRECTORY_SEPARATOR . $this->skinName);
//                 }
//                 break;

//             default :

//                 break;
//         }

        //$view->addScriptPath(APPLICATION_PATH . '/views/'.$this->skinName);
        //Zend_Debug::dump($view);

        //$viewRenderer->setViewBasePathSpec(':moduleDir/views/' . $this->skinName . '/');

        if(!$this->appSettings->enableFbOpenGraph) {
        	$view->doctype('HTML5');
        }
        elseif($this->appSettings->enableFbOpenGraph) {
        	$view->doctype('XHTML1_RDFA');
        }
        $view->addHelperPath("Dxcore/View/Helper", "Dxcore_View_Helper");
        //$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $view->addHelperPath(APPLICATION_PATH . '/modules/pages/views/helpers', 'Pages_View_Helper');
        $view->addHelperPath(APPLICATION_PATH . '/modules/default/views/helpers', 'Aurora_View_Helper');
        $view->addHelperPath('System/View/Helpers', 'System_View_Helper');
        $view->addHelperPath('Stelias/View/Helpers', 'Stelias_View_Helper');
        //$view->addHelperPath('System/Dojo/View/Helpers', 'System_Dojo_View_Helper');
        // custom for stelias
        $view->addHelperPath(APPLICATION_PATH . '/modules/festival/views/helpers', 'Festival_View_Helper');

        

        Zend_Dojo::enableView($view);
        $view->dojo()
        ->setDjConfigOption('parseOnLoad', true)
        ->setDjConfigOption('ioPublish', true);
        $view->dojo()->useCdn();
        $view->dojo()->setCdnBase('//ajax.googleapis.com/ajax/libs/dojo/');
        $view->dojo()->setCdnVersion('1.9.1');
        $view->dojo()->setCdnDojoPath('/dojo/dojo.js');
        $view->dojo()->addStyleSheetModule('dijit.themes.claro');
        $view->dojo()->Enable();

        $jquery = $view->jQuery();
        $jquery->useCdn();
        $jquery->setVersion('1.9.1');
        $jquery->useUiCdn();
        $jquery->setUiVersion('1.10.2');
        $jquery->enable();
        $jquery->uiEnable();

        //ZendX_JQuery_View_Helper_JQuery::enableNoConflictMode();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

    }
    // Init the Navigation helper - Requires Dxcore library
    protected function _initNavigation() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
    	/**
    	 * This will be changing soon to use a module based navigation
    	 */
		// Read navigation XML and initialize container
        $navconfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $container = new Zend_Navigation($navconfig);
        // Register navigation container
        $registry = Zend_Registry::getInstance();
        $registry->set('Zend_Navigation', $container);
        // Add action helper
        Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_Navigation());
    }
    protected function _initAdminNavigation() {
    	//$this->_logger->info('Bootstrap ' . __METHOD__);
    	/**
    	* This will be changing soon to use a module based navigation
    	*/
    	// Read navigation XML and initialize container
    	$adminnavconfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'adminnav');
    	$admincontainer = new Zend_Navigation($adminnavconfig);
    	// Register navigation container
    	$registry = Zend_Registry::getInstance();
    	$registry->set('Admin_Navigation', $admincontainer);
    	// Add action helper
    	Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_AdminNavigation());
    }
    protected function _initSubPageNavigation() {
        Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_SubPageNav());
    }
    protected function _initSearchWidget()
    {
        //Zend_Controller_Action_HelperBroker::addHelper(new Search_Controller_Action_Helper_SearchWidget());
    }
    protected function _initAdminInfoWidget()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_AdminInfo());
    }
    protected function _initMenuWidget()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Stelias_Controller_Action_Helper_Menu());
    }

    // Init the pageTitle plugin - Requires the Dxcore library and ini namespace
    protected function _initPagetitle() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
		//TODO: Recode this plugin to allow for modules
        $pageTitle = new Dxcore_Controller_Plugin_Pagetitle();
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Dxcore_Controller_Plugin_Pagetitle($pageTitle));
    }
    /**
     * Setup locale
     */
    protected function _initLocale() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
    	$this->locale = new Zend_Locale('en_US');
        Zend_Registry::set('Zend_Locale', $this->locale);
    }
    protected function _initCurrency() {
		$currency = new Zend_Currency('en_US');
		Zend_Registry::set('Zend_Currency', $currency);
    }
    // Set today's date for an instance of Zend_Date
    protected function _initDebugTime() {
        //$this->_logger->info('Bootstrap ' . __METHOD__);
        // Date may be retrieved from the registry using the today_date key
        //$now = Zend_Date::now();
        //$date = $today->toString('yyyy-MM-dd');
        $date = new Zend_Date();
        $registry = Zend_Registry::getInstance();
        $registry->set('debug_start_time', $date->getTimestamp());
    }
//     protected function _initLicense() {
//         	//$this->_logger->info('Bootstrap ' . __METHOD__);
//         	//TODO: Recode this plugin to allow for modules
//         	$License = new Dxcore_Controller_Plugin_License();
//         	$front = Zend_Controller_Front::getInstance();
//         	$front->registerPlugin(new Dxcore_Controller_Plugin_License($License));
//     }
}