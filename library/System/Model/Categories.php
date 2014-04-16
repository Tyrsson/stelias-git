<?php

/**
 * Categories
 *  
 * @author Joey
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class System_Model_Categories extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'categories';
    protected $_primary = 'catId';
    protected $_sequence = true;
    protected $_rowClass = 'System_Model_Row_Category';
    
    
    public function fetchDropDown() {
        $query = $this->select()
        ->from($this->_name, array('key' => 'catId', 'value' => 'catName'))
        ->where('catId > ?', 0);
    
        return  $this->fetchAll($query);
    }
}
