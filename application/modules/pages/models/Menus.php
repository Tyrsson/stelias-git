<?php
/**
 * Menus
 *  
 * @author Joey
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Pages_Model_Menus extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'pageMenus';
    protected $_primary = 'menuId';
    protected $_sequence = true;
}
