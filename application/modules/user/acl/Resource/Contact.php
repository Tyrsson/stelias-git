<?php
class User_Acl_Resource_Contact implements Zend_Acl_Resource_Interface
{

    protected $_resourceId;

    public function __construct($resourceId = 'content')
    {
        $this->_resourceId = (string) $resourceId;
    }

    public function getResourceId()
    {
        return $this->_resourceId;
    }

    public static function getPrivileges()
    {
        $privs = array();
        return $privs;
    }

    public function __toString()
    {
        return $this->getResourceId();
    }
}