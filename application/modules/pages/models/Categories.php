<?php
require_once ('Zend/Acl/Resource/Interface.php');
require_once ('Zend/Db/Table/Abstract.php');
class Pages_Model_Categories extends Zend_Db_Table_Abstract implements Zend_Acl_Resource_Interface
{
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * 
     */
    protected $_name = 'pageCategories';
    /**
     * The primary key column or columns.
     * A compound key should be declared as an array.
     * You may declare a single-column primary key
     * as a string.
     *
     * @var mixed
     */
    protected $_primary = 'catId';
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
    protected $_rowClass = 'Pages_Model_Row_Category';
    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'Pages_Model_Rowset_Categories';

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
                                			'columns' => 'userId',
                                			'refTableClass' => 'User_Model_Users',
                                			'refColumns' => 'userId'
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
    protected $_dependentTables = array(
                                        'Pages_Model_Files', 
                                        'Pages_Model_Tags',
                                        'Pages_Model_Lookup');

    protected $_defaultSource = self::DEFAULT_NONE;
    protected $_defaultValues = array();
    

    public function fetch($id) {
        
        $query = $this->select()->from($this->_name)->where('catId = ?', $id);
        
        return  $this->fetchRow($query);
    }
    public function fetchAlbumList($userId) {
         
        $query = $this->select()->from($this->_name, array('catId', 'catName'))->where('userId = ?', $userId);
    
        return  $this->fetchAll($query);
    }
	public function fetchUserAlbums($id) {
	    
        $query = $this->select()
        			  ->from($this->_name, array('key' => 'catId', 'value' => 'catName'))
                      ->where('userId = ?', $id);
        
        return  $this->fetchAll($query);
    }

    public function test($userId)
    {
        if(isset($userId))
        {
            $id = $userId;
        }
        $query = $this->select()
                      ->from($this->_name, array('catId', 'catName'))
                      ->where('userId = ?', Zend_Auth::getInstance()->hasIdentity() ? Zend_Auth::getInstance()->getIdentity()->userId : $id);
                      
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
?>