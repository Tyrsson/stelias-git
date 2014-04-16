<?php

/**
 * MenuItems
 *
 * @author Joey
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Festival_Model_MenuItems extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'festivalmenuitems';
    protected $_primary = 'id';
    protected $_sequence = true;
    protected $menuName;
    protected $appSettings;
    protected $currentMenuId;

    public function init(){
        $this->appSettings = Zend_Registry::get('appSettings');
        $menu = new Festival_Model_Menu();
        $this->currentMenuId = $menu->fetchCurrentMenuId();
    }
    public function fetchPrice($itemId) {
        $sql = $this->select()->from($this->_name, array('id', 'price'))->where('menuId = ?', $this->currentMenuId)->where('id = ?', $itemId);
        $row = $this->fetchRow($sql);
        return $row->price;
    }
    public function fetchName($itemId) {
    	$sql = $this->select()->from($this->_name, array('id', 'itemName'))->where('menuId = ?', $this->currentMenuId)->where('id = ?', $itemId);
    	$row = $this->fetchRow($sql);
    	return $row->itemName;
    }
    public function fetchItems($menuId)
    {
        $sql = $this->select()->from($this->_name, '*')->where('menuId = ?', $menuId)->order('order ASC');
        return $this->fetchAll($sql);
    }
    public function fetchItemForManagement($menuId, $itemId)
    {
        $sql = $this->select()->from($this->_name, '*')
                              ->where('menuId = ?', $menuId)
                              ->where('id = ?', $itemId);

        return $this->fetchRow($sql);
    }
    public function fetchCurrentMenu() {
        $menu = new Festival_Model_Menu();
        $currentMenu = $menu->fetchCurrent();

        $sql = $this->select()->from($this->_name, '*')->where('menuId = ?', $currentMenu->menuId)->order('order ASC');
        return $this->fetchAll($sql);
    }
    public function fetchCurrentItemsByType($type)
    {
    	$menu = new Festival_Model_Menu();
    	$currentMenu = $menu->fetchCurrent();

    	$sql = $this->select()->from($this->_name, '*')->where('menuId = ?', $this->currentMenuId)->where('itemType = ?', $type)->order('order ASC');
    	return $this->fetchAll($sql);
    }
    public function fetchItemTypes()
    {
    	$q = $this->select()->distinct()->from($this->_name, array('itemType'))->where('itemType != ?', "");
    	return $this->fetchAll($q)->toArray();

    }
}
