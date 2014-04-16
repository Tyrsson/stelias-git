<?php
/**
 *
 * @author Joey Smith
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class System_Controller_Action_Helper_AdminNavigation extends Zend_Controller_Action_Helper_Abstract
{
	protected $_adminContainer;
	// constructor, set navigation container
	public function __construct(Zend_Navigation $container = null)
	{
		if (null !== $container)
		{
			$this->_adminContainer = $container;
		}
	}
	// check current request and set active page
	public function preDispatch()
	{
	    // temp fix to add php 5.4 support
		@$this->getContainer ()->findBy ( 'uri', $this->getRequest ()->getRequestUri () )->active = true;
	}
	// retrieve navigation container
	public function getContainer()
	{
		if (null === $this->_adminContainer)
		{
			$this->_adminContainer = Zend_Registry::get ( 'Admin_Navigation' );
			if($this->getRequest()->getControllerName() != 'admin.install')
			{
				//self::getModuleSettingsPages();
			}
			self::getPageModuleAdminPage();
		}
		if (null === $this->_adminContainer)
		{
			throw new RuntimeException ( 'Navigation container unavailable' );
		}
		return $this->_adminContainer;
	}
	public function getPageModuleAdminPage() {
	    $pageTable = new Page_Model_Page();
	    $dbPages = $pageTable->fetchParents()->toArray();
	    $mainTab = array(
	            'label' => 'Pages',
	            'uri'   => '/admin/page/create'
	    );
	    $index = count($dbPages);
	    for ($i = 0; $i < $index; $i++) {
	        $children = $pageTable->fetchDependents($dbPages[$i]['pageId'])->toArray();
	        $mainTab['pages'][$i] = array(
	                'label'      => $dbPages[$i]["pageName"],
	                'uri'        => '/admin/page/edit/' . $dbPages[$i]['pageUrl']

	        );
	        if(count($children)) {
	            $childCount = count($children);
	            for ($n = 0; $n < $childCount; $n++) {
	                $mainTab['pages'][$i]['pages'][] = array(
	                        'label'      => $children[$n]['pageName'],
	                        'uri'        => '/admin/page/edit/'.$children[$n]['pageUrl']
	                );
	                continue;
	            }
	        }
	        continue;
	    }
	    $this->_adminContainer->addPage($mainTab);
	}
	public function getModuleSettingsPages()
	{
		$model = new Admin_Model_ModuleSettings();
		$select = $model->select()->from('modulesettings', array('moduleName'))->where('moduleName != ?', '')->where('moduleName != ?', 'admin')->group('moduleName');
		$result = $model->fetchAll($select);
		//Zend_Debug::dump($result);

		/*
		 * <label>General Settings</label>
                <uri>/admin/settings</uri>
                <resource>admin:area</resource>
                <privilege>manage-all</privilege>
                <order>100</order>
		 */

		$parent = array(
                'label' => 'Settings',
                'uri' => '/admin/settings',
		        'resource' => 'admin:area',
		        'privilege' => 'manage-all',
		        'order'     => '100'
        );

		$index = count($result);
		for ($i = 0; $i < $index; $i++) {
			$parent['pages'][$i] = array(
			        'label' => ucfirst($result[$i]->moduleName) . ' Settings',
			        'uri' => '/admin/settings/' . $result[$i]->moduleName,
			        'order' => $i
			);
		}
		$this->_adminContainer->addPage($parent);
	}
}