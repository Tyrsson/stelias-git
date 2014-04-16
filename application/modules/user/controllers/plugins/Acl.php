<?php
class User_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public $_acl;
    public $_role;
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        $acl = new User_Acl_Acl();
        $this->_acl = $acl;
        //$this->user = new User_Model_Users();
        /**
         * The below sets the default $acl and Acl role to be used in 
         * the navigation container
         */
        self::setAcl($this->_acl);
        $this->_role = self::getUserRole();
        self::setRole($this->_role);
    }
    protected function getUserRole()
    {
        return new User_Acl_Role_User();
    }
    protected function setRole ($role)
    {
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);
    }
    protected function setAcl ($acl)
    {
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
    }
}