<?php

/**
 * Zend_Db_Table_Abstract
 *  
 * @author jsmith
 * @version 
 */
class Testimonials_Model_Testimonials extends Zend_Db_Table_Abstract
{
	/**
	 * The default table name 
	 */
    protected $_name     = 'testimonials';
    protected $_primary  = 'id';
    protected $_sequence = true;
    protected $_rowClass = 'Testimonials_Model_Row_Entry';
    
    public function init()
    {
    	//$this->user = new User_Model_UserManager();
    	$this->user = new User_Model_User();
    }

	public function getOnePage($page = 1, $selectApprovedOnly = true)
	{
		if ((bool)$selectApprovedOnly) {
			$query = $this->select()
				->from($this->_name, array('id', 'guestName', 'content', 'rating', 'isApproved', 'createdDate', 'updatedDate'))
				->where('isApproved = ?', 1)
				->order('id DESC');
		} else {
			$query = $this->select()
				->from($this->_name, array('id', 'guestName', 'content', 'rating', 'isApproved', 'createdDate', 'updatedDate'))
				->order('id DESC');
		}
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
		$paginator->setItemCountPerPage(4);
		$paginator->setCurrentPageNumber($page);

		return $paginator;
	}

    public function getById($id)
    {
    	$query = $this->select()->from($this->_name)->where('id = ?', $id);
    	return $this->fetchRow($query);
    }

    public function fetch($id)
    {
    	$query = $this->select()->from($this->_name)->where('id = ?', $id);
    	return $this->fetchRow($query);
    }
}

