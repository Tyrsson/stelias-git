<?php
class User_Acl_Resource_Gallery implements Zend_Acl_Resource_Interface
{

    protected $_resourceId;

    public function __construct($resourceId = 'gallery')
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