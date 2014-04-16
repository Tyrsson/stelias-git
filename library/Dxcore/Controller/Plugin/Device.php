<?php
class Dxcore_Controller_Plugin_Device extends Zend_Controller_Plugin_Abstract {
	
    /**
     * 
     * @var (object) WURFL Manager instance
     */
	protected $wurfl;
	/**
	 * 
	 * @var (array) Config stored in the application ini
	 */
	protected $wurflConfig;
	/**
	 * 
	 * @var (array) $_SERVER[$index]
	 */
	protected $server;
	/** 
	 * 
	 * @var (string) UserAgent from Http headers
	 */
	protected $useragent;
	/**
	 * 
	 * @var (object) Zend_Controller_Request_Http
	 */
	protected $http;
	/**
	 * 
	 * @var (object) Zend_Application_Bootstrap_Bootstrap
	 */
	protected $bootstrap;
	/**
	 * 
	 * @var (object) Zend_Application_Resource
	 */
	protected $resource;
	/**
	 * 
	 * @var (object) Zend_Layout
	 */
	protected $layout;
	/**
	 * 
	 * @var (array) Array of config options set in application.ini
	 */
	protected $options;
	/**
	 * 
	 * @var (object) WURFL device handler for requesting device
	 */
	public    $device;
	/**
	 * 
	 * @var (boolean) true if requesting device is mobile
	 */
	public    $isMobile;
	/**
	 * 
	 * @var (boolean) true if requesting device is desktop
	 */
	public    $isDesktop;
	/** 
	 * 
	 * @var (object) Zend_View
	 */
	public    $view;
	/**
	 * 
	 * @var (array) Array of device features returned from the wurfl device handler
	 */
	public    $features;
	/**
	 * 
	 * @var (string) Device type (mobile, desktop)
	 */
	public    $type;

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) 
	{
		try {
			// get the bootstrap
			$this->bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
			// get the layout resource from the boostrap
			$this->layout = $this->bootstrap->getResource('layout');
			// get the bootstrap options
			$this->options = $this->bootstrap->getOptions();
			// get the wurfl config from the ini
			$this->wurflConfig = $this->options['wurfl'];
			// Get the server proxy for $_SERVER[$index]
			self::getServer();
			// Get only the useragent in case we need it and only it
			self::getUserAgent();
			// build the wurfl manager instance
			self::buildWurfl($this->wurflConfig);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		// At this point all vars are defined for use
		 self::getType();
		 self::getFeatures();
		 //Zend_Debug::dump(array($this->type, $this->features));
	        switch (true) {
            case ($this->type == 'mobile'):
                $this->layout->setLayout('mobile');
                break;
            case ($this->type == 'desktop'):
                $this->layout->setLayout('desktop');
                break;
                
            default:
                // use default layout.phtml
                break;
        } 
	}
	protected function buildWurfl($wurflConfig)
	{

		try {
			define("WURFL_DIR", $this->wurflConfig['wurfl_lib_dir']);
			require_once(WURFL_DIR . 'Application.php');
			// Create WURFL Configuration from an XML config file
			$wurflConfig = new WURFL_Configuration_XmlConfig($this->wurflConfig['config-file']);
			// Create a WURFL Manager Factory from the WURFL Configuration
			$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
			// Create a WURFL Manager ($wurflManager is a WURFL_WURFLManager object)
			$this->wurfl = $wurflManagerFactory->create();
			// Get information on the loaded WURFL ($wurflInfo is a WURFL_Xml_Info object)
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	protected function getDevice()
	{
		$this->device = $this->wurfl->getDeviceForHttpRequest(self::getServer());
		return $this->device;
	}
	protected function getType()
	{
		$device = self::getDevice();
		if($device->getCapability('is_wireless_device') == 'true')
		{
			$this->isMobile = true;
			$this->type = 'mobile';
		} 
		elseif($device->getCapability('is_wireless_device') == 'false')
		{
			$this->isDesktop = true;
			$this->type = 'desktop';
		}
	}
	protected function getFeatures()
	{
		$device = self::getDevice();
		$this->features = $device->getAllCapabilities();
		return $this->features;
	}
	protected function getHttp()
	{
		$this->http = new Zend_Controller_Request_Http();
		return $this;
	}
	protected function getServer()
	{
		self::getHttp();
		$this->server = $this->http->getServer();
		return $this->server;
	}
	protected function getUserAgent()
	{
		self::getHttp();
		$this->useragent = $this->http->get('HTTP_USER_AGENT');
		return (string) $this->useragent;
	}
	protected function getView()
	{
		$this->view = $this->bootstrap->getResource('view');
		return $this->view;
	}
}