<?php
/**
 *
 * @author Joey Smith
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class Dxcore_Controller_Action_Helper_AdminNavigation extends Zend_Controller_Action_Helper_Abstract
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
				$this->_adminContainer->addPages(self::getModuleSettingsPages());
			}
		}
		if (null === $this->_adminContainer)
		{
			throw new RuntimeException ( 'Navigation container unavailable' );
		}
		return $this->_adminContainer;
	}
	public function getModuleSettingsPages()
	{
		//create a table obj
		$model = new Admin_Model_ModuleSettings();

		$select = $model->select()->from('modulesettings', array('moduleName'))->where('moduleName != ?', '');

		$result = $model->fetchAll($select);

		$pagename = array();
		foreach ($result as $page)

			if($page['moduleName'] === 'admin') :
				unset($page);
			else:
				$pagename[] = $page['moduleName'];
			endif;

		$pages = array();
		$i = 2;
		$pagenames = array_unique($pagename, SORT_STRING);
		foreach ($pagenames as $page) :

				$pages[] = Zend_Navigation_Page::factory(array(
						'label' => ucfirst($page) . ' Settings',
						'uri'   => '/admin/settings/' . $page,
						'order' => $i
				)
				);

		++$i;
		endforeach;

		return $pages;
	}
}