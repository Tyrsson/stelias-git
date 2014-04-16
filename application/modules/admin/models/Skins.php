<?php
/**
 * Skins
 *  
 * @author Joey
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_Skins extends Zend_Db_Table_Abstract
{
    protected $_primary = 'skinId';
    protected $_sequence = true;
    /**
     * The default table name
     */
    protected $_name = 'skins';
    
    public function fetchById($skinId) {
        $query = $this->select()->from($this->_name, array("*"))->where('skinId = ?', $skinId);
        return $this->fetchRow($query);
    }
    public function fetchSelect()
    {
        $query = $this->select()
        ->from($this->_name, array('key' => 'skinId', 'value' => 'skinName'));
        
        return  $this->fetchAll($query);
    }
    public function fetchCurrent() {
        $query = $this->select()->from($this->_name, array("*"))->where('isCurrentSkin = ?', 1);
        return $this->fetchRow($query);
    }
    public function fetchDropDown() {
        $query = $this->select()
        			  ->from($this->_name, array('key' => $this->_primary, 'value' => $valueColumn))
        			  ->where($condition);
        
        return  $this->fetchAll($query);
    }
}
