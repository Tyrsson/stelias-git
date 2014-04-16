<?php
class User_Acl_Resource_News extends Dxcore_Model_News implements Zend_Acl_Resource_Interface
{
	 protected $_resourceId;
     protected $_identity;
     protected $_acl;
     protected $_role;

    public function getResourceId()
    {
        $this->_resourceId = $this->_name;
        return $this->_resourceId;
    }
    public function setRole($role)
    {
        if(!isset($role))
        $this->_role = new User_Acl_Role_User;
        return $this;
    }
    public function getRole()
    {
        $this->_role = new User_Acl_Role_User();
        return $this->_role;
    }
    public function checkAcl($privilege)
    {
        ///$auth = Zend_Auth::getInstance();
        $acl = new Dxcore_Acl(Zend_Auth::getInstance());
        return $acl->isAllowed($this->getRole(), $this, $privilege);
    }
    public function save(array $data)
    {
        if (!$this->checkAcl('save')) 
        {
            throw new User_Acl_Exception('Insufficient Privileges');
        }
    }
    public function edit(array $data, $id)
    {
        if (!$this->checkAcl('editown') || !$this->checkAcl('admin:edit'))
        {
            throw new User_Acl_Exception('Insufficient Privileges');
        }
        else {
            
                $where = $this->getAdapter()->quoteInto('newId = ?', $id);
                $this->update($data, $where);
        }
    }
    public function submit($data)
    {
        
        if (!$this->checkAcl('submit') || !$this->checkAcl('save'))
        {
            throw new User_Acl_Exception('Insufficient Privileges');
        }
        else {
                $latestNewsId = $this->insert($data);
                return $latestNewsId;
        }
    }
    public function deleteOne($id)
    {
        if (!$this->checkAcl('deleteown') || !$this->checkAcl('admin:delete'))
        {
            throw new User_Acl_Exception('Insufficient Privileges');
        }
        else 
        {
            $where = $this->getAdapter()->quoteInto('newsId = ?', $id);
            if (isset($id) && isset($where)) 
            {
                if($this->delete($where))
                {
                    return true;
                } 
                else {
                    return false;
                }
            }          
        }
    }
    public function __toString()
    {
        return $this->getResourceId();
    }

}