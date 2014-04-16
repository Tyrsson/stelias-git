<?php
/**
 * System_Model_Acl
 * 
 * @author jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class System_Model_Acl extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'acl';
    protected $_primary = 'role';
    
    public function getRoles()
    {
        $query = $this->select()->from('acl', 'role');
        $result = $this->fetchAll($query);
        //Zend_Debug::dump($result, true);
        return $result;
    }
    public function getResourcesByRole($role)
    {
        $query = $this->select()
                      ->from('acl', array('role', 'resources'))
                      ->where('role = ?', $role);
                      
        $result = $this->fetchAll($query);
        return $result;
    }
}
