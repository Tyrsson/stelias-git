<?php
/**
 * Menu
 * 
 * @author jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class System_Model_Menu extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'menu';
    protected $_primary = 'page_id';
}
