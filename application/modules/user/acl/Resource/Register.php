<?php
class User_Acl_Resource_Register implements Zend_Acl_Resource_Interface
{

    protected $_resourceId;

    public function __construct($resourceId = 'register')
    {
        $this->_resourceId = (string) $resourceId;
    }

    public function getResourceId()
    {
        return $this->_resourceId;
    }

    public function getPrivileges()
    {
        $privs = array();
        return $privs;
    }

    public function __toString()
    {
        return $this->getResourceId();
    }
}