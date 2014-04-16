<?php
/**
 * Roles
 *  
 * @author Joey
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class User_Model_Roles extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'roles';
    protected $_primary = 'roleId';
    protected $_sequence = true;
    
    public function fetchRoleById($roleId)
    {
        $query = $this->select()->from($this->_name, array('roleId', 'role'))->where('roleId = ?', $roleId);
        $row = $this->fetchRow($query);
        return $row->role;
    }
    public function fetchIdByRole($roleName) {
        $query = $this->select()->from($this->_name, array('roleId', 'role'))->where('role = ?', $roleName);
        $row = $this->fetchRow($query);
        return $row->roleId;
    }
    public function fetchRoles()
    {
        $query = $this->select()
        ->from($this->_name, array('role'));
        return $this->fetchAll($query);
    }
    public function fetchAllRoles()
    {
    	$query = $this->select()->from($this->_name, array('key' => 'roleId', 'value' => 'role'));
    	return $this->fetchAll($query)->toArray();
    }
}
