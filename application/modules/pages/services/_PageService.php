<?php
abstract class Pages_Service_PageService implements Zend_Acl_Resource_Interface
{
	const USER_ID    				= 'userId';
	const PAGE_OWNER 				= 'pageOwner';
	const PAGE_ID    				= 'pageId';
	const PARENT_ID  				= 'parentId';
	const ROLE       				= 'role';
	const PAGE_NAME  				= 'pageName';
	const PAGE_URL                  = 'pageUrl';
	const VISIBILITY 				= 'visibility';
	const CREATED_DATE 				= 'createdDate';
	const MODIFIED_DATE 			= 'modifiedDate';
	const ARCHIVED_DATE  			= 'archivedDate';
	const PAGE_ORDER     			= 'pageOrder';
	const PAGE_TYPE      			= 'pageType';
	const PAGE_TEXT      			= 'pageText';
	const PAGE_TABLE_CLASS 			= 'pageTableClass';
	const PAGE_ROW_CLASS 			= 'pageRowClass';
	const PAGE_ROWSET_CLASS 		= 'pageRowsetClass';
	const PAGE_TABLE_LOOKUP         = 'pageTableLookup';
	//const MENU_ID          		= 'menuId';
	const CAT_ID           			= 'categoryId';
	const IS_ALLOWED                = 'isAllowed';
	const KEY_WORDS                 = 'keyWords';
	const SHOW_SLIDER               = 'showSlider';
	const SHOW_IN_HOME_WIDGET       = 'showInHomeWidget';

	/*
	 * @var int User_Model_User::userId
	 */
	private   $_userId;
	/*
	 * @var int User_Model_User::role
	*/
	private   $_role;
	protected   $_pdbt;
	/**
	 *
	 * @var (object) Pages_Model_Row_Page
	 */
	private   $_page;
	private   $_isAllowed = false;
	protected $_pageId;
	protected $_parentId;
	protected $_parentPage;
	protected $_pageName;
	protected $_pageUrl;
	protected $_visibility;
	protected $_createdDate;
	protected $_publishDate;
	protected $_modifiedDate;
	protected $_archivedDate;
	protected $_pageOrder;
	protected $_pageType;
	protected $_pageText;
	protected $_pageTableClass = 'Pages_Model_Pages';
	protected $_pageRowClass = 'Pages_Model_Row_Page';
	protected $_pageTableLookup = 'Pages_Model_PageLookup';
	protected $_pageRowsetClass = 'Pages_Model_Rowset_Pages';
	protected $_hasSubPages = false;
	protected $_hasMenu     = false;
	protected $_subPages = array();
	protected $_menus    = array();
	protected $_files    = array();
	protected $_pageComments = array();
	protected $_customSettings = array();
	protected $_mainImage = null;
	protected $_thumbNail = null;
	protected $_keyWords = null;
	public $showSlider = null;
	public $showInHomeWidget = false;

	protected $_acl;
	protected $_auth;
	protected $_requestingUser = null;
	//protected $_pageLookup = null;
	protected $_pageOwner = null;
	protected $_pageData = array();
	protected $_options = array();
	protected $_userModel;
	public    $found = false;

	protected $bool;

	public function __construct($pageUrl)
	{
	    $this->bool = new Zend_Filter_Boolean(array('type' => array(Zend_Filter_Boolean::ALL), 'casting' => false));
		$this->_pdbt = new $this->_pageTableClass();
		$this->_userModel = new User_Model_User();
		self::setAuth();
		self::setRequestingUser();

		if($pageUrl == null)
		{
			self::setPage($this->_pdbt->fetchNew());
			$this->_pageOwner = self::getRequestingUser();
			self::setRole($this->_pageOwner['role']);
			return $this;
		}
		else {

			$this->setPage($this->_pdbt->fetchByUrl($pageUrl));
			if($this->_page !== null)
			{
				$this->setFound(true);
				if($this->_requestingUser !== null)
				{
					$user = $this->_userModel->fetch($this->_page->userId);
					$this->setPageOwner($user->toArray());
				}
				self::setOptions($this->_page->toArray());
				self::setSubPages($this->_pdbt->fetchChildren($this->_pageId));
				self::setParentPage($this->_pdbt->fetchById($this->_page->parentId));

			}
			self::setAcl();
		    //self::checkAcl($this->_acl);

			return $this;
		}
	}
	public function setOptions(Array $options)
	{
		foreach ($options as $key => $value) {
		    $value = $this->bool->filter($value);
			switch ($key) {
				case self::USER_ID:
					$this->setUserId($value);
					break;
				case self::PAGE_OWNER:
					$this->setPageOwner($value);
					break;
				case self::PAGE_ID:
					$this->setPageId($value);
					break;
				case self::PARENT_ID:
					$this->setParentId($value);
					break;
				case self::ROLE:
					$this->setRole($value);
					break;
				case self::PAGE_NAME:
					$this->setPageName($value);
					break;
				case self::PAGE_URL:
				    $this->setPageUrl($value);
				    break;
				case self::VISIBILITY:
					$this->setVisibility($value);
					break;
				case self::CREATED_DATE:
					$this->setCreatedDate($value);
					break;
				case self::MODIFIED_DATE:
					$this->setModifiedDate($value);
					break;
				case self::ARCHIVED_DATE:
					$this->setArchivedDate($value);
					break;
				case self::PAGE_ORDER:
					$this->setPageOrder($value);
					break;
				case self::PAGE_TYPE:
					$this->setPageType($value);
					break;
				case self::PAGE_TEXT:
					$this->setPageText($value);
					break;
				case self::KEY_WORDS:
						$this->setPageKeyWords($value);
						break;
				case self::SHOW_SLIDER:
						$this->setShowSlider($value);
						break;
				case self::SHOW_IN_HOME_WIDGET:
				        $this->setShowInHomeWidget($value);
				        break;
				default:
					// ignore unrecognized configuration directive
					break;
			}
		}
		return $this;
	}


	/**
     * @return the $showInHomeWidget
     */
    public function getShowInHomeWidget ()
    {
        return $this->showInHomeWidget;
    }

	/**
     * @param boolean $showInHomeWidget
     */
    protected function setShowInHomeWidget ($value)
    {
        $this->showInHomeWidget = $value;
    }

	/**
     * @return the $_pageUrl
     */
    public function getPageUrl ()
    {
        return $this->_pageUrl;
    }

	/**
     * @param field_type $_pageUrl
     */
    protected function setPageUrl ($value)
    {
        $this->_pageUrl = $value;
    }

	public function setPageKeyWords($keywords) {
		$this->_keyWords = $keywords;
	}
	public function setFound($found)
	{
		$this->found = $found;
	}
	public function setShowSlider($value) {
		$this->showSlider = $value;
	}
	public function getShowSlider() {
		return $this->showSlider;
	}
	/**
	 * @param int $_userId
	 */
	protected function setUserId($_userId) {
		$this->_userId = (int) $_userId;
	}
	/**
	 * @param string $_role
	 */
	protected function setRole($_role) {
		$this->_role = $_role;
	}
	/**
	 * @param Pages_Model_Pages $_pdbt
	 */
	protected function setPdbt($_pdbt) {
		$this->_pdbt = $_pdbt;
	}
	/**
	 * @param object $_page
	 */
	protected function setPage($_page) {
		$this->_page = $_page;
	}
	/**
	 * @param field_type $_pageId
	 */
	protected function setPageId($_pageId) {
		$this->_pageId = (int) $_pageId;
	}
	/**
	 * @param int $_parentId
	 */
	protected function setParentId($_parentId) {
		$this->_parentId = (int) $_parentId;
	}
	protected function setParentPage($parentPage) {
	    $this->_parentPage = $parentPage;
	}
	/**
	 * @param string $_pageName
	 */
	protected function setPageName($_pageName) {
		$this->_pageName = $_pageName;
	}
	/**
	 * @param string $_visibility
	 */
	protected function setVisibility($_visibility) {
		$this->_visibility = $_visibility;
	}
	/**
	 * @param int | string $_createdDate
	 */
	protected function setCreatedDate($_createdDate) {
		$this->_createdDate = $_createdDate;
	}
	/**
	 * @param int | string $_publishDate
	 */
	protected function setPublishDate($_publishDate) {
		$this->_publishDate = $_publishDate;
	}
	/**
	 * @param int | string $_modifiedDate
	 */
	protected function setModifiedDate($_modifiedDate) {
		$this->_modifiedDate = $_modifiedDate;
	}
	/**
	 * @param int | string $_archivedDate
	 */
	protected function setArchivedDate($_archivedDate) {
		$this->_archivedDate = $_archivedDate;
	}
	/**
	 * @param int $_pageOrder
	 * proxies to Zend_Navigation
	 */
	public function setPageOrder($_pageOrder) {
		$this->_pageOrder = $_pageOrder;
	}
	/**
	 * @param string $_pageType type of module page this object will proxy
	 */
	protected function setPageType($_pageType) {
		$this->_pageType = $_pageType;
	}
	/**
	 * @param string $_pageText
	 */
	protected function setPageText($_pageText) {
		$this->_pageText = $_pageText;
	}
	/**
	 * @param string $_pageTableClass
	 */
	protected function setPageTableClass($_pageTableClass) {
		$this->_pageTableClass = $_pageTableClass;
	}
	/**
	 * @param string $_pageRowClass
	 */
	protected function setPageRowClass($_pageRowClass) {
		$this->_pageRowClass = $_pageRowClass;
	}
	/**
	 * @param Pages_Model_Rowset_Pages $_pageRowsetClass
	 */
	protected function setPageRowsetClass($_pageRowsetClass) {
		$this->_pageRowsetClass = $_pageRowsetClass;
	}
	/**
	 * @param boolean $_hasSubPages
	 */
	protected function setHasSubPages($_hasSubPages) {
		$this->_hasSubPages = $_hasSubPages;
	}
	/**
	 * @param boolean $_hasMenu
	 */
	protected function setHasMenu($_hasMenu) {
		$this->_hasMenu = $_hasMenu;
	}
	/**
	 * @param multitype: $_subPages
	 */
	protected function setSubPages($_subPages) {
	    $this->setHasSubPages(true);
		$this->_subPages = $_subPages;
	}
	/**
	 * @param multitype: $_menus
	 */
	protected function setMenus($_menus) {
		$this->_menus = $_menus;
	}
	/**
	 * @param multitype: $_files
	 */
	protected function setFiles($_files) {
		$this->_files = $_files;
	}
	/**
	 * @param multitype: $_pageComments
	 */
	protected function setPageComments($_pageComments) {
		$this->_pageComments = $_pageComments;
	}
	/**
	 * @param multitype: $_customSettings
	 */
	protected function setCustomSettings($_customSettings) {
		$this->_customSettings = $_customSettings;
	}
	/**
	 * @param NULL $_mainImage
	 */
	protected function setMainImage($_mainImage) {
		$this->_mainImage = $_mainImage;
	}
	/**
	 * @param NULL $_thumbNail
	 */
	protected function setThumbNail($_thumbNail) {
		$this->_thumbNail = $_thumbNail;
	}
	/**
	 * @param object $_acl User_Acl_Acl
	 */
	protected function setAcl($_acl = null) {
		if($_acl == null)
		{
			$_acl = new User_Acl_Acl();
		}
		$this->_acl = $_acl;
	}
	/**
	 * @var (object) Zend_Auth
	 */
	protected function setAuth(Zend_Auth $_auth = null)
	{
	    if(!$_auth instanceof Zend_Auth)
	    {
	        $_auth = Zend_Auth::getInstance();
	    }
		$this->_auth = $_auth;
	}
	/**
	 * @param Zend_Auth | int userId $_requestingUser
	 */
	protected function setRequestingUser($_requestingUser = null) {

	    if($_requestingUser == null)
	    {
	        /** If we have not passed a userId then we want the current user
	         *  This allows for an admin reuqest to the page
	         */
	        if($this->_auth instanceof Zend_Auth)
	        {
	            // Are they logged in?
	            if($this->_auth->hasIdentity())
	            {
	                $id = $this->_auth->getIdentity()->userId;
	            }
	        }
	    }
	    elseif($_requestingUser !== null && $_requestingUser instanceof Zend_Auth)
	    {
	    	if($this->_auth instanceof Zend_Auth)
	    	{
	    		// Are they logged in?
	    		if($this->_auth->hasIdentity())
	    		{
	    			$id = $this->_auth->getIdentity()->userId;
	    		}
	    	}
	    }
	    elseif($_requestingUser !== null && ! $_requestingUser instanceof Zend_Auth ) {
	        $id = (int) $_requestingUser;
	    }
	    if(isset($id))
	    {
	        $uTable = new User_Model_User();
	        $result = $uTable->fetch($id);
	       // Zend_Debug::dump($result);
	        $this->_requestingUser = $result->toArray();
	    }
	}
	/**
	 * @param  $_isAllowed
	 */
	final private function checkAcl($_acl = null) {
		if($_acl == null)
		{
			$_acl = $this->getAcl();
		}

		if( $_acl->isAllowed(new User_Acl_Role_User(), $this, "pages.$this->_role.view")   )
		{
		    $this->_isAllowed = true;
		}
	}
	/**
	 * @param array $_pageOwner
	 */
	protected function setPageOwner($_pageOwner) {
	    if(is_array($_pageOwner))
	    {
		    foreach($_pageOwner as $key => $value)
		    {
		        $this->_pageOwner[$key] = $value;
		    }
	    } else {
	        $this->_pageOwner = $_pageOwner;
	    }
	}
	/**
	 * @param multitype: $_pageData
	 */
	protected function setPageData($_pageData) {
		$this->_pageData = $_pageData;
	}
	/**
	 * @return the $_userId
	 */
	public function getUserId() {
		return $this->_userId;
	}
	public function getKeyWords()
	{
		return $this->_keyWords;
	}
	/**
	 * @return the $_role
	 */
	public function getRole() {
		return $this->_role;
	}
	/**
	 * @return the $_pdbt
	 */
	public function getPdbt() {
		return $this->_pdbt;
	}
	/**
	 * @return the $_page
	 */
	public function getPage() {
		return $this->_page;
	}
	/**
	 * @return the $_pageId
	 */
	public function getPageId() {
		return $this->_pageId;
	}
	/**
	 * @return the $_parentId
	 */
	public function getParentId() {
		return $this->_parentId;
	}
	public function getParentPage() {
	    return $this->_parentPage;
	}
	/**
	 * @return the $_pageName
	 */
	public function getPageName() {
		return $this->_pageName;
	}
	/**
	 * @return the $_visibility
	 */
	public function getVisibility() {
		return $this->_visibility;
	}
	/**
	 * @return the $_createdDate
	 */
	public function getCreatedDate() {
		return $this->_createdDate;
	}
	/**
	 * @return the $_publishDate
	 */
	public function getPublishDate() {
		return $this->_publishDate;
	}
	/**
	 * @return the $_modifiedDate
	 */
	public function getModifiedDate() {
		return $this->_modifiedDate;
	}
	/**
	 * @return the $_archivedDate
	 */
	public function getArchivedDate() {
		return $this->_archivedDate;
	}
	/**
	 * @return the $_pageOrder
	 */
	public function getPageOrder() {
		return $this->_pageOrder;
	}
	/**
	 * @return the $_pageType
	 */
	public function getPageType() {
		return $this->_pageType;
	}
	/**
	 * @return the $_pageText
	 */
	public function getPageText() {
		return $this->_pageText;
	}
	/**
	 * @return the $_pageTableClass
	 */
	public function getPageTableClass() {
		return $this->_pageTableClass;
	}
	/**
	 * @return the $_pageRowClass
	 */
	public function getPageRowClass() {
		return $this->_pageRowClass;
	}
	/**
	 * @return the $_pageRowsetClass
	 */
	public function getPageRowsetClass() {
		return $this->_pageRowsetClass;
	}
	/**
	 * @return the $_hasSubPages
	 */
	public function getHasSubPages() {
		return $this->_hasSubPages;
	}
	/**
	 * @return the $_hasMenu
	 */
	public function getHasMenu() {
		return $this->_hasMenu;
	}
	/**
	 * @return the $_subPages
	 */
	public function getSubPages() {
		return $this->_subPages;
	}
	/**
	 * @return the $_menus
	 */
	public function getMenus() {
		return $this->_menus;
	}
	/**
	 * @return the $_files
	 */
	public function getFiles() {
		return $this->_files;
	}
	/**
	 * @return the $_pageComments
	 */
	public function getPageComments() {
		return $this->_pageComments;
	}
	/**
	 * @return the $_customSettings
	 */
	public function getCustomSettings() {
		return $this->_customSettings;
	}
	/**
	 * @return the $_mainImage
	 */
	public function getMainImage() {
		return $this->_mainImage;
	}
	/**
	 * @return the $_thumbNail
	 */
	public function getThumbNail() {
		return $this->_thumbNail;
	}
	/**
	 * @return the $_acl
	 */
	public function getAcl() {
		return $this->_acl;
	}
	public function getAuth() {
		return Zend_Auth::getInstance();
	}
	/**
	 * @return the $_requestingUser
	 */
	public function getRequestingUser() {
		return $this->_requestingUser;
	}
	/**
	 * @return the $_isAllowed
	 */
	public function getIsAllowed() {
		return $this->_isAllowed;
	}
	/**
	 * @return the $_pageOwner
	 */
	public function getPageOwner() {
		return $this->_pageOwner;
	}
	/**
	 * @return the $_pageData
	 */
	public function getPageData() {
		return $this->_pageData;
	}
	/* (non-PHPdoc)
	 * @see Zend_Acl_Resource_Interface::getResourceId()
	 */

	public function getResourceId() {
		 $resource = 'pages';
		 return $resource;
	}
}