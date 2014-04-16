<?php
/**
 * User
 *
 * @author Joey
 * @version
 */

class User_Model_DbTable_User extends Zend_Db_Table_Abstract implements
Zend_Acl_Resource_Interface
{
    public $isLogged = false;
    protected $_uStatus;
    /**
     *
     * @var (array) $_neededModels = array('form' => 'Zend_Form');
     * create class properties that correspond to the array index as object instances of the array values string name
     */
    protected $_neededModels;
    /**
     * Acl related vars
     */
    protected $_resourceId;
    protected $_identity;
    protected $_role;
    protected $_acl;
    protected $_auth;
    /**
     * The default table name
     */
    protected $_name = 'user';
    //protected $_primary = 'userId';
    protected $_sequence = true;
    protected $_rowClass = 'User_Model_Row_User';
    protected $_rowsetClass = 'User_Model_Rowset_Users';

    public function init ()
    {

    }

    public static function getName() {
    	return self::$_name;
    }
    // check if user has a valid session
    public function checkSession ()
    {
        self::setAuth();
        if ($this->_auth->hasIdentity()) {
            $this->isLogged = true;
            return true;
        } else {
            return false;
        }
    }
    public function getRole()
    {
        self::_setRole();
        return (string) $this->_role;
    }
    // Set auth object
    protected function setAuth ()
    {
        $this->_auth = Zend_Auth::getInstance();
    }
	/**
	 *
	 * @param $userId (int)
	 */
	public function fetch($id) {

		$query = $this->select()
					  ->from( $this->_name, array ('userId', 'role', 'userName', 'firstName', 'lastName', 'email', 'uStatus', 'registeredDate', 'hash') )
					  ->where ( 'userId = ?', $id );

		$result = $this->fetchRow ( $query );

		return $result;
	}
    final protected function _setRole()
    {
    	$this->_role = new User_Acl_Role_User();
    }

    public function setCompanyName($companyname)
    {
    	$this->companyName = $companyname;
    }
    public function logOut()
    {
        if(self::checkSession())
        {
            $this->_auth->clearIdentity();
            Zend_Session::destroy();
            if(Zend_Session::isDestroyed())
            {
                return true;
            } else {
                return false;
            }
        }
    }
    public function __set ($key, $value)
    {
        $this->$key = $value;
    }
    /**
     * Zend_Acl_Resource
     */
    public function getUserRoleById($id)
    {
    	//Zend_Debug::dump($id);
    	if(null !== $id) {
    	$query = $this->select()
    					->from('user', array('userId', 'role'))
    					->where('userId = ?', $id);

    	$result = $this->fetchRow($query);

    	if(isset($result->role) && !empty($result->role))

    	return $result->role;
    	} else {
    		throw new Zend_Db_Exception('column role not returned');
    	}
    }
    /**
     * (non-PHPdoc)
     * @see Zend_Acl_Resource_Interface::getResourceId()
     */
    public function getResourceId ()
    {
        $this->_resourceId = $this->_name;
        return $this->_resourceId;
    }
    public function isAllowed($resource, $privilege) {
    	return $this->_acl->isAllowed(self::getRole(), $resource, $privilege);
    }
    final public function checkAcl ($privilege)
    {
        return $this->_acl->isAllowed(self::getRole(), $this, $privilege);
    }
    public function getTableName() {
    	return $this->_name;
    }

}
