<?php

/**
 * DxcoreController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Dxcore_Controller_Action extends Zend_Controller_Action {

    const MOBILE_SKIN_DIR = 'mobile';

    public $isAdmin = false;
	/**
	 *
	 * @var object $_uManager Dxcore_Model_UserManager
	 */
	protected $_uManager;
	/**
	 *
	 * @var object $_logger Dxcore_Log
	 */
	public $log;
	/**
	 *
	 * @var array $_POST
	 */
	public $post;
	/**
	 *
	 * @var array $_GET
	 */
	public $get;
	/**
	 *
	 * @var object Zend_Validate_Alnum
	 */
	private $_Alnum;
	/**
	 *
	 * @var object Zend_Validate_Alpha
	 */
	private $_Alpha;
	/**
	 *
	 * @var object Zend_Validate_Regex
	 */
	private $_regex;
	/**
	 *
	 * @var unknown_type
	 */
	private $_html;
	private $_validatorChain;
	public $filters;
	/**
	 *
	 * @var object $form Zend_Form
	 */
	public $form;
	/**
	 *
	 * @var object $appSettings Dxcore_Settings
	 */
	public $appSettings;
	public $moduleSettings;
	public $cleanData;
	/**
	 *
	 * @var object $auth Zend_Auth
	 */
	public $auth;
	/**
	 *
	 * @var string Zend_Controller_RequestAbstract
	 */
	public $requestedUrl;
	public $requestUri;
	/**
	 *
	 * @var object $messenger Flashmessenger
	 */
	public $messenger;
	/**
	 *
	 * @var array $messages Messages stack
	 */
	public $messages; // message stack for flashmessenger
	/**
	 * $var $userId
	 */
	public $userId;
	/**
	 *
	 * @var object Zend_Db_Table_Row
	 */
	public $user;

	/**
	 *
	 * @var bool logged in status of current user
	 */
	public $isLogged;
	/**
	 *
	 * @var object $acl User_Acl_Acl
	 */
	public $acl;
	/**
	 *
	 * @var string Module Name
	 */
	public $module;
	/**
	 *
	 * @var string controller name
	 */
	public $controller;
	/**
	 *
	 * @var string action name
	 */
	public $action;
	/**
	 * @var (string) $requestUri requested uri
	 */
	protected $pageName;

	public $scheme;
	public $host;
	public $context;
	protected $_isAjax = false;
	/**
	 *
	 * @var (bool) flag for mobile devices
	 */
	public $isMobile = false;
	/**
	 *
	 * @var (object) device object
	 */
	public $device;
	protected $_ns;
 	public    $fb = null;
 	public    $lang;
 	public    $keyWords;
 	protected $requestIp;
 	protected $server;
 	public    $skinName;
 	public    $loginWidget;
 	public    $searchWidget;
 	public    $searchIndexPath;
 	public    $previousUri;
 	public    $referingModule = null;
 	protected $_cache = null;
	public function init() {

		parent::init ();
		$this->log = Zend_Registry::get('log');
		$this->_cache = Zend_Registry::get('cache');
		//Zend_Debug::dump($this->_cache);
		// application and module settings, module settings are merged with application settings at the model layer
		$this->searchIndexPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'search';
		$this->appSettings = Zend_Registry::get('appSettings');

		$lang = new Admin_Model_Language();
		$this->lang = $lang->fetchAll();
		// server and request vars
		$this->scheme = $this->_request->getScheme();
		Zend_Registry::set('serverScheme', $this->scheme);

		$this->host = $this->_request->getHttpHost();
		//$this->previousUri = $this->_request->getServer('HTTP_REFERER');
// 		if($this->previousUri === "$this->scheme://$this->host" || $this->previousUri === '/') {
// 		    $this->referingModule = 'pages';
// 		}
		//$this->server = $this->_request->getServer();
		$this->requestIp = $this->_request->getServer('REMOTE_ADDR');
		$this->module = $this->_request->getModuleName();
		$this->controller = $this->_request->getControllerName();
		$this->action = $this->_request->getActionName();

		//$this->_ns = new Zend_Session_Namespace($this->module);

		// Zend Auth - authentication
		$this->auth = Zend_Auth::getInstance ();
		// Acl - authorization
		$this->acl = new User_Acl_Acl ();
		///// NEW
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->acl);

		///// End New
		// requested url
		$this->requestedUrl = $this->_request->getRequestUri ();
		// only to provide both url and uri so you have both (its just a preference thing)
		$this->requestUri = $this->requestedUrl;
		// user manager
		$this->_uManager = new User_Model_User ();

		switch($this->auth->hasIdentity()) {
		    case true:
		            if(isset(Zend_Auth::getInstance()->getIdentity()->userId)) {
		                $this->isLogged = true;
		                $this->user = $this->_uManager->fetch ( $this->auth->getIdentity ()->userId );
		                $this->userName = $this->user->userName;
		            }
		        break;

		    case false :
		        $this->user = new stdClass();
		        $this->isLogged = false;
		        $this->user->role = new User_Acl_Role_Guest();
		        break;

		    default :
		        $this->isLogged = false;
		        break;
		}
		// DO NOT REMOVE THIS LINE OR JOEY WILL CUT YOUR FINGERS OFF !!!!!!!!!!!!!!!!!!!!!!!
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($this->user->role);

		$this->messenger = $this->_helper->getHelper ( 'FlashMessenger' );
		if ($this->messenger->getMessages ()) {
			$this->view->messages = $this->messenger->getMessages ();
		}
		switch(IS_MOBILE) {
		    case true :
		        $this->isMobile = true;
		        //$this->_helper->layout()->setLayout('mobile');
		        break;
		    default :
		        $this->isMobile = false;
		        break;
		}
		switch($this->_request->isPost()) {
		    case true :
		        $this->post = $this->_request->getPost ();
		        break;
		}
		switch($this->_request->isGet()) {
		    case true :
		        $this->get = $this->_request->getParams ();
		        break;
		}
		switch($this->_request->isXmlHttpRequest()) {
		    case true :
		        $this->_isAjax = true;
		        break;
		}
		switch($this->appSettings->enableFbOpenGraph) {
		    case true :
		        $this->view->showFb = true;
		        $this->fb = new Facebook_Facebook(array(
		                'appId' => $this->appSettings->facebookAppId,
		                'secret' => $this->appSettings->facebookAppSecret)
		        );
		        break;
		    case false :
		        $this->view->showFb = false;
		        break;
		}
		if(isset($this->_request->pageUrl)) {
		   // $this->pageName = $this->_request->pageUrl;
		    $this->pageUrl = $this->_request->pageUrl;
		}
		//Zend_Debug::dump($this->_request->pageUrl);
		if($this->_request->pageUrl) {
		    $this->isHome = false;
		    $this->view->isHome = false;
		} 
		switch($this->_request->pageUrl) {
		    case '/':
		    case 'home':
		        $this->isHome = true;
		        $this->view->isHome = true;
		        break;
		    default:
		        $this->isHome = false;
		        $this->view->isHome = false;
		        break;
		}

// 		$bootstrap = $this->getInvokeArg('bootstrap');
// 		$userAgent = $bootstrap->getResource('useragent');
// 		$this->device = $userAgent->getDevice();

		$skin = new Admin_Model_Skins();
		$row = $skin->fetchCurrent();
		$this->skinName = $row->skinName;


        /*
		 * Below are all the properties that can be accessed from the view object
		 * Please see above for assignment
		 */
		$this->view->skinName = $this->skinName;
		$this->view->isMobile = $this->isMobile;
		$this->view->module = $this->module;
		$this->view->controller = $this->controller;
		$this->view->action = $this->action;
		$this->view->scheme = $this->scheme;
		$this->view->user = $this->user;
		$this->view->host = $this->host;
		$this->view->isLogged = $this->isLogged;
		$this->view->isAdmin = $this->isAdmin;
		$this->view->appSettings = $this->appSettings;
		$this->view->lang = $this->lang;
		$this->view->keyWords = $this->keyWords;
        //Zend_Debug::dump($this->user);
	}
	public function isAjax()
	{
	    return $this->_isAjax;
	}

}
