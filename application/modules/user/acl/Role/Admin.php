<?php
class User_Acl_Role_Admin implements Zend_Acl_Role_Interface
{

    protected $_roleId;
    public $_inheritsFrom = 'mod';

    public function __construct($roleId = 'admin')
    {
        $this->_roleId = (string) $roleId;
    }

    public function getRoleId()
    {
        return (string) $this->_roleId;
    }

    public function __toString()
    {
        return $this->getRoleId();
    }
}