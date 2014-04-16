<?php
class User_Acl_Role_Mod implements Zend_Acl_Role_Interface
{

    protected $_roleId;
    public $_inheritsFrom = 'user';

    public function __construct($roleId = 'mod')
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