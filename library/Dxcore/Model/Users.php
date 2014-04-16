<?php
/**
 * Users
 * 
 * @author Jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Dxcore_Model_Users extends Zend_Db_Table_Abstract 
{
    /**
     * The default table name 
     */
    protected $_name = 'user';
    protected $_primary = 'userIduserId';
    protected $_sequence = true;
    protected $_dependentTables = array('Dxcore_Model_News', 'Dxcore_Model_Pages', 'Dxcore_Model_Links', 'Dxcore_Model_Profile');

    
    public function getOnePage($page = 1)
    {
        $query = $this->select();
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setItemCountPerPage(8);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }


}
