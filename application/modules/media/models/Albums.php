<?php

/**
 * Albums
 *
 * @author Joey Smith
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';
class Media_Model_Albums extends Zend_Db_Table_Abstract 
{
	/**
	 * The default table name
	 */
	protected $_name = 'mediaalbums';
	protected $_primary = 'albumId';
	protected $_sequence = true;
	protected $_rowClass = 'Media_Model_Row_Album';
    

	public function fetchIdByAlbumName($albumName) 
	{
		$query = $this->select()->from($this->_name, array('albumId', 'albumName'))->where('albumName = ?', $albumName);
		$result = $this->fetchRow($query);
		if(isset($result->albumId))
			return $result->albumId;
	}
	public function fetchForCkFinderRename($albumName)
	{
	    $query = $this->select()->from($this->_name, array('albumId', 'albumName', 'userId', 'serverPath'))->where('albumName = ?', $albumName);
	    return $this->fetchRow($query);
	}
	public function fetchById($id) 
	{
	    $query = $this->select()->from($this->_name, array('albumId', 'parentId', 'albumName', 'userId', 'role', 'passWord', 'albumDesc', 'serverPath'))->where('albumId = ?', $id);
	    return $this->fetchRow($query);
	}
	public function fetchParentByChildName($childName) 
	{
	    $query = $this->select()->from($this->_name, array('albumId', 'parentId', 'albumName', 'serverPath'))->where('albumName = ?', $childName);
	    $row = $this->fetchRow($query);
	    return $this->fetchById($row->parentId);
	}
	public function fetchForCkFinderDelete($albumName)
	{
		$query = $this->select()->from($this->_name, array('albumId', 'albumName', 'userId'))->where('albumName = ?', $albumName);
		return $this->fetchRow($query);
	}
	public function fetchSubAlbums($parentId) {
	    $sql = $this->select()->from($this->_name, array('albumId', 'parentId', 'albumName', 'userId', 'role', 'passWord', 'albumDesc', 'serverPath'))->where('parentId = ?', $parentId);
	    return $this->fetchAll($sql);
	}

	public function fetchAllByParentId($parentId) 
	{
	    $sql = $this->select()->from($this->_name, array('albumId', 'parentId', 'albumName', 'userId', 'role', 'passWord', 'albumDesc', 'serverPath'))->where('parentId = ?', $parentId);
	    return $this->fetchAll($sql);
	}
	public function fetchAlbumByName($albumName)
	{
		$query = $this->select()->from($this->_name, array('albumId', 'parentId', 'albumName', 'userId', 'role', 'passWord', 'albumDesc', 'serverPath'))->where('albumName = ?', $albumName);
		return $this->fetchRow($query);
	}
	public function fetchSubAlbumPage($perPage = 12, $page, $parentId) 
	{
	    $sql = $this->select()->where('parentId = ?', $parentId);
	    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($sql));
	    $paginator->setItemCountPerPage($perPage);
	    $paginator->setCurrentPageNumber($page);
	    return $paginator;
	    
	}
	public function fetchPage($perPage = 6, $page)
	{
		$query = $this->select()
		              ->where('parentId = ?', -2)
		              //->where('albumId = ?', -2)
		              ->where('albumId != ?', -3)
		              ->where('albumId != ?', -1);
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
		$paginator->setItemCountPerPage($perPage);
		$paginator->setCurrentPageNumber($page);
		return $paginator;
	}

}