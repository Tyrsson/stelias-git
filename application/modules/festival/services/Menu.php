<?php
class Festival_Service_Menu
{
    public $menu;
    public $menuId;
    public $items;
    public $menuData;
    public $itemData;
    public $menuRow;
    public $isNewMenu = false;
    public function __construct($menuId = null)
    {
        $this->menu = new Festival_Model_Menu();
        $this->items = new Festival_Model_MenuItems();
        
        if($menuId === null) {
            $this->menuRow = $this->menu->fetchNew();
            $this->isNewMenu = true;
        }
        else {
            $this->menuRow = $this->menu->fetchRow($this->menu->select()->where('menuId = ?', $menuId));
        }
    }
    
    public function init()
    {
        
    }
	/**
     * @return the $menu
     */
    public function getMenu ()
    {
        return $this->menu;
    }

	/**
     * @param Festival_Model_Menu $menu
     */
    public function setMenu ($menu)
    {
        $this->menu = $menu;
    }

	/**
     * @return the $menuId
     */
    public function getMenuId ()
    {
        return $this->menuId;
    }

	/**
     * @param field_type $menuId
     */
    public function setMenuId ($menuId)
    {
        $this->menuId = $menuId;
    }

	/**
     * @return the $items
     */
    public function getItems ()
    {
        return $this->items;
    }

	/**
     * @param Festival_Model_MenuItems $items
     */
    public function setItems ($items)
    {
        $this->items = $items;
    }

	/**
     * @return the $menuData
     */
    public function getMenuData ()
    {
        return $this->menuData;
    }

	/**
     * @param field_type $menuData
     */
    public function setMenuData ($menuData)
    {
        $this->menuData = $menuData;
    }

	/**
     * @return the $itemData
     */
    public function getItemData ()
    {
        return $this->itemData;
    }

	/**
     * @param field_type $itemData
     */
    public function setItemData ($itemData)
    {
        $this->itemData = $itemData;
    }

    
}