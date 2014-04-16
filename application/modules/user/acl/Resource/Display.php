<?php
class User_Acl_Resource_Display implements Zend_Acl_Resource_Interface
{

    protected $_resourceId;

    public function __construct($resourceId = 'display')
    {
        $this->_resourceId = (string) $resourceId;
    }

    public function getResourceId()
    {
        return $this->_resourceId;
    }

    public static function getPrivileges()
    {
        $privs = array('showpage');
        return $privs;
    }

    public function __toString()
    {
        return $this->getResourceId();
    }
}