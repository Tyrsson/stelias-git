<?php
class Pages_Acl_Assert_PageAccess implements Zend_Acl_Assert_Interface
{
	public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $page = null, $privilege = null)
	{

		if(!$role instanceof User_Acl_Role_User)
		{
			throw new InvalidArgumentException(__CLASS__ . '::' . __METHOD__ . ' expects the role to be an instance of User_Acl_Role_User');
		}
		if(!$page instanceof Pages_Model_Row_Page)
		{
			throw new InvalidArgumentException(__CLASS__ . '::' . __METHOD__ . ' expects the resource to be an instance of Pages_Model_Row_Page');
		}
		//return false;
		$minAccessRole = $page->role;
		// This means they are the page owner
// 		if($role->getRoleId())
// 		{
// 			return true;
// 		}
		if($role->getRoleId() == $minAccessRole)
		{
			return true;
		}
		elseif($minAccessRole === 'guest')
		{
			return true;
		} else {
			return false;
		}
	}
}