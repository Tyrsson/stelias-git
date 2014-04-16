<?php

/**
 * Menu
 *
 * @author Joey
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Festival_Model_Menu extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'festivalmenus';
    protected $_primary = 'menuId';
    protected $_sequence = true;
    protected $appSettings;

    public function init(){
        $this->appSettings = Zend_Registry::get('appSettings');
    }
    public function fetch($menuId)
    {
        $sql = $this->select()->from($this->_name)->where('menuId = ?', $menuId);
        return $this->fetchRow($sql);
    }
    public function fetchIdByName()
    {
        $sql = $this->select()->from($this->_name)->where('name = ?', $this->appSettings->currentMenu);
        return $this->fetchRow($sql);
    }
    public function fetchCurrent()
    {
        //$this->appSettings->currentMenu = '2014 Festival Menu';
        $sql = $this->select()->from($this->_name)->where('name = ?', $this->appSettings->currentMenu);
        return $this->fetchRow($sql);
    }
    public function fetchNameById($id)
    {
        $sql = $this->select()->from($this->_name, array('menuId', 'name'))->where('menuId = ?', $id);
        return $this->fetchRow($sql);
    }
    public function fetchMenuList()
    {
        $sql = $this->select()->from($this->_name);
        return $this->fetchAll($sql);
    }
    public function fetchCurrentMenuId() {
    	$sql = $this->select()->from($this->_name, array('menuId'))->where('name = ?', $this->appSettings->currentMenu);
    	$result = $this->fetchRow($sql);
    	return $result->menuId;
    }
}
