<?php
class User_Acl_Resource_Content implements Zend_Acl_Resource_Interface
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
    public function getPrivileges()
    {
        $privs = array('submit', 'editown', 'deleteown');
    }

    public function __toString()
    {
        return $this->getResourceId();
    }
}
