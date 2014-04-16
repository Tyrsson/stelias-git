<?php
require_once ('Zend/Acl/Resource/Interface.php');
require_once ('Zend/Db/Table/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * @package Pages
     */
class Pages_Model_Files extends Zend_Db_Table_Abstract implements Zend_Acl_Resource_Interface
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $_name = 'pageFiles';
    /**
     * The primary key column or columns.
     * A compound key should be declared as an array.
     * You may declare a single-column primary key
     * as a string.
     *
     * @var mixed
     */
    protected $_primary = 'fileId';
    /**
     * Define the logic for new values in the primary key.
     * May be a string, boolean true, or boolean false.
     *
     * @var mixed
     */
    protected $_sequence = true;
    /**
     * Classname for row
     *
     * @var string
     */
    protected $_rowClass = 'Pages_Model_Row_File';
    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'Pages_Model_Rowset_Files';
    /**
     * Associative array map of declarative referential integrity rules.
     * This array has one entry per foreign key in the current table.
     * Each key is a mnemonic name for one reference rule.
     *
     * Each value is also an associative array, with the following keys:
     * - columns       = array of names of column(s) in the child table.
     * - refTableClass = class name of the parent table.
     * - refColumns    = array of names of column(s) in the parent table,
     *                   in the same order as those in the 'columns' entry.
     * - onDelete      = "cascade" means that a delete in the parent table also
     *                   causes a delete of referencing rows in the child table.
     * - onUpdate      = "cascade" means that an update of primary key values in
     *                   the parent table also causes an update of referencing
     *                   rows in the child table.
     *
     * @var array
     */
    protected $_onUpdate = 'cascade';
    protected $_OnDelete = 'cascade';
    
	protected $_referenceMap = array(
		'User' => array(
			'columns' => 'userId',	// the column in the 'media_files' table which is used for the join
			'refTableClass' => 'users',	// the users table name
			'refColumns' => 'userId'	// the primary key of the users table
		)
	);
    /**
     * Simple array of class names of tables that are "children" of the current
     * table, in other words tables that contain a foreign key to this one.
     * Array elements are not table names; they are class names of classes that
     * extend Zend_Db_Table_Abstract.
     *
     * @var array
     */
    protected $_dependentTables = array('Pages_Model_PageLookup'); 

    protected $_defaultSource = self::DEFAULT_NONE;
    protected $_defaultValues = array();
    
    public function fetchPage($pageId, $page) {
    	$query = $this->select()
    	//->setIntegrityCheck(false)
    	->from($this->_name, array('pageId', 'fileName'))
    	//->join('media_tags', 'media_tags.fileId = media_files.fileId', 'filetag');
    	->where('pageId = ?', $pageId);
    	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
    	$paginator->setItemCountPerPage(2);
    	$paginator->setCurrentPageNumber($page);
    	return $paginator;
    }
    public function fetchByFileName($fileName) {
        $query = $this->select()->from($this->_name)->where('fileName = ?', $fileName);
        
        return  $this->fetchRow($query);
    }
    
    public function fetch($id) {
    
    	$query = $this->select()->from($this->_name)->where('fileId = ?', $id);
    
    	return  $this->fetchRow($query);
    }
    
    public function getAlbumByFileId($fileId)
    {
        $query = $this->select()
                      ->from($this->_name, array('fileId', 'fileName'))
                      ->where('fileId = ?', $fileId);
                      
        $result = $this->fetchAll($query);
        return $result;
    }
    
    public function getResourceId()
    {
        $this->_resourceId = $this->_name;
        return $this->_resourceId;
    }



    public function __toString()
    {
        return $this->getResourceId();
    }
}