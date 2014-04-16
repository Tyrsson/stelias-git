<?php
class User_Acl_Role_Guest implements Zend_Acl_Role_Interface
{

    protected $_roleId;
    protected $_inheritsFrom = null;

    public function __construct($roleId = 'guest')
    {
        $this->_roleId = (string) $roleId;
    }

    public function getRoleId()
    {
        return $this->_roleId;
    }

    public function __toString()
    {
        return $this->getRoleId();
    }
}