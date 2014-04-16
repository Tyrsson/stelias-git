<?php
class User_Acl_Role_User implements Zend_Acl_Role_Interface
{

    protected $_roleId;
    public $_inheritsFrom = 'guest';
    public $_defaultRole = 'user';

    public function __construct()
    {
    	return $this->getRoleId();
    }

    public function getRoleId()
    {
    	$this->user = new User_Model_DbTable_User();

    	//Zend_Debug::dump($var)
    	try {
    	    switch(Zend_Auth::getInstance()->hasIdentity()) {
    	        case true :
    	            if(isset(Zend_Auth::getInstance()->getIdentity()->userId)) {
    	                $this->_roleId = $this->user->getUserRoleById(Zend_Auth::getInstance()->getIdentity()->userId);
    	                return  $this->_roleId;
    	            }
    	            break;

    	        case false :
    	            $this->_roleId = new User_Acl_Role_Guest();
    	            return $this->_roleId;
    	            break;
    	        default:
    	             //throw new Zend_Acl_Exception('Unknown role id');
    	            break;
    	    }
    	} catch (Exception $e) {
    	    echo $e->getMessage();
    	}



//     	if(Zend_Auth::getInstance()->hasIdentity() && !isset(Zend_Auth::getInstance()->getIdentity()->userId) && substr(Zend_Auth::getInstance()->getIdentity(), 0, 10) === 'DIREXTION\\') {
//     	    $this->_roleId = 'dxadmin';
//     	    return $this->_roleId;
//     	}

//         if(Zend_Auth::getInstance()->hasIdentity() && isset(Zend_Auth::getInstance()->getIdentity()->userId))
//         {
//             $this->_roleId = $this->user->getUserRoleById(Zend_Auth::getInstance()->getIdentity()->userId);
//             return (string) $this->_roleId;
//         }
//         elseif(!Zend_Auth::getInstance()->hasIdentity() )
//         {
//             $this->_roleId = new User_Acl_Role_Guest();
//             return (string) $this->_roleId;
//         }
//         else {
//             throw new Zend_Controller_Action_Exception('Invalid user roleId', 110);
//         }
    }
    public function getDefaultRole()
    {
        return $this->_defaultRole;
    }
    public function getPrivsByRole($role)
    {
       //TODO: Design db table to hold privs
    }
    private function setPrivs()
    {
       //TODO: implement
    }
    public function __toString()
    {
        return (string) $this->getRoleId();
    }

}