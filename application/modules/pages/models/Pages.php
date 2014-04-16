<?php
/**
 * Pages
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';
class Pages_Model_Pages extends Zend_Db_Table_Abstract implements Zend_Acl_Resource_Interface
{

    public $form;
    public $formSchema;
    /**
     * The default table name
     */
    protected $_name = 'pages';
    protected $_primary = 'pageId';
    protected $_sequence = true;
    public $parentId;

    /**
     * Classname for row
     *
     * @var string
     */
    protected $_rowClass = 'Pages_Model_Row_Page';
    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'Pages_Model_Rowset_Pages';

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
    protected $_referenceMap = array('Owner' => array(
                    								'columns' => array('pageId'),
                    								'refTableClass' => 'Pages_Model_PageLookup',
                    								'refColumns' => array('pageId'),
                    								'onDelete'   => 'cascade',
                    								'onUpdate'   => 'cascade'
                       							),
                       				 'parentId' => array(
                       								'columns' => array('parentId'),
                       								'refTableClass' => 'Pages_Model_PageLookup',
                       								'refColumns' => array('parentId'),
                       								'onDelete' => 'cascade',
                       								'onUpdate' => 'cascade'
                       							)
   								);
    // protected $_dependentTables = array();
    public $log;

    public function init($parentId = null) {
        $this->log = Zend_Registry::get('log');
        self::setParentId($parentId);
    }
    public function fetchPageNameByType($type = 'media')
    {
        $query = $this->select()->from($this->_name, array('pageName', 'pageType'))->where('pageType = ?', $type);

        switch($type) {
            case 'media' :
                    $result = $this->fetchRow($query);
                    return $result->pageName;
                break;

            default :
                return $this->fetchAll($query);
                break;
        }
    }
    public function allowCreateType($type = 'media')
    {

        switch($type) {
            case 'media' :
                $query = $this->select()
                ->from($this->_name, array('pageType'))
                ->where('pageType = ?', $type);

                $result = $this->fetchAll($query);
                if(isset($result->pageType) && !empty($result->pageType) && $result->pageType === 'media') {
                    return false;
                } else {
                    return true;
                }
                break;
            default:
                return true;
                break;
        }

    }

    public function fetchTotalPageCount() {
    	return count($this->fetchAll());
    }
    public function fetchParentPageCount() {
    	$query = $this->select()->from($this->_name, array())->where('parentId =?', 0);
    	return count($this->fetchAll($query));
    }
    public function getPagesForOrder() {
    	$query = $this->select()
    					->from($this->_name, array('pageId', 'pageName', 'pageOrder'))
    					->where('parentId = ?', 0)
    					->order('pageOrder ASC');
    	return $this->fetchAll($query);
    }
    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }
    public function fetchById($id) {
        $query = $this->select()->where('pageId = ?', $id);
        return $this->fetchRow($query);
    }
    public function fetchChildren($parentId) {
        $query = $this->select()
        				->from($this->_name, array('pageId', 'parentId', 'pageName','pageUrl', 'pageText', 'pageType', 'showSlider', 'showInHomeWidget', 'role', 'pageOrder'))
        				->where('parentId = ?', $parentId)
        				//->where('pageType = ?', 'page')
        				;
        $result = $this->fetchAll($query);
        return $result;
    }
    public function fetchParentDropDown() {
    	$noParent = array();

        $query = $this->select()->from($this->_name, array('key' => 'pageId', 'value' => 'pageName'));
        $result = $this->fetchAll($query)->toArray();
        //Zend_Debug::dump($result);
        array_unshift($result, array('key' => 0, 'value' => 'No Parent'));

        return $result;
    }
    public function fetchPageFileByFileName($fileName) {
        $files = new Pages_Model_Files();
        return $files->fetchFileName($fileName);
    }

    public function fetchMobilePages(array $pageNames) {
    
        $pages = array();
        
       // $i = 0;
        foreach($pageNames as $page) {
            $query = $this->select()
            //->setIntegrityCheck(false)
            ->from($this->_name, array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'keyWords', 'showSlider', 'showInHomeWidget'))
            //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
            ->where('pageName = ?', $page);
    
            $result = $this->fetchRow($query);
            if($result !== null) {
                $pages[] = $result;
            }
            
        }
        //Zend_Debug::dump($pages);
        //die('just before return');
        return $pages;
    }
    public function fetchWidgetData() {

    }
    public function fetchForEditByName($pageName) {
    	$query = $this->select()
    	//->setIntegrityCheck(false)
    	->from($this->_name, array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'showSlider', 'showInHomeWidget'))
    	//->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
    	->where('pageName = ?', $pageName);
    	return $this->fetchRow($query);
    }
    public function fetchForEditByUrl($pageUrl) {
        $query = $this->select()
        //->setIntegrityCheck(false)
        ->from($this->_name, array('pageId', 'parentId', 'role', 'userId', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'showSlider', 'showInHomeWidget'))
        //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        ->where('pageUrl = ?', $pageUrl);
        return $this->fetchRow($query);
    }
    public function fetchMainMenu($options = null, $keys = null)
    {
        if($options == null)
        {
            $options['visibility'] = 'Public';
        }
        if($keys == null)
        {
            $keys = array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText');
        }
        $query = $this->select()
        ->from($this->_name, $keys)
        //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        //->where('visibility = ?', $options['visibility'])
        ->where('parentId = ?', 0);
        return $this->fetchAll($query);
    }
    public function fetchParentById($parentId) {
        $query = $this->select()
        //->setIntegrityCheck(false)
        ->from($this->_name, array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'showSlider', 'showInHomeWidget'))
        //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        ->where('parentId = ?', $parentId)
        ->where('parentId != ?', 0);
        return $this->fetchRow($query);
    }
    public function fetchByName($pageName) {
        $query = $this->select()
        			//->setIntegrityCheck(false)
        			->from($this->_name, array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'keyWords', 'showSlider', 'showInHomeWidget'))
        			//->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        			->where('pageName = ?', $pageName);
        return $this->fetchRow($query);
    }
    public function fetchByUrl($pageUrl) {
        $query = $this->select()
        //->setIntegrityCheck(false)
        ->from($this->_name, array('pageId', 'parentId', 'userId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'keyWords', 'showSlider', 'showInHomeWidget'))
        //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        ->where('pageUrl = ?', $pageUrl);
        return $this->fetchRow($query);
    }
    public function fetchByType($pageType, $nameOnly = false, $childrenOnly = false, $order = null)
    {
        $query = $this->select();
        //->setIntegrityCheck(false)
        $query->from($this->_name, array('pageId', 'parentId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'modifiedDate', 'archivedDate', 'pageOrder', 'pageType', 'pageText', 'keyWords', 'showSlider', 'showInHomeWidget'));
        //->join('pageLookup', 'pageLookup.pageId ='.$this->_name .'.pageId', 'userId')
        //->where('pageType = ?', $pageType)        
        
        if(is_array($pageType)) {
            
            $query->where('pageType IN (?)', $pageType);
            
        }
        elseif(is_string($pageType)) {
            $query->where('pageType = ?', $pageType);
        }
        
        if($childrenOnly) {
            $query->where('parentId > ?', '0');
        }
        if($order !== null)
        $query->order('pageId '. $order);
        //Zend_Debug::dump($query);
        
        $row = $this->fetchAll($query);

        if($nameOnly && $row instanceof Zend_Db_Table_Row_Abstract) {
            return $row->pageName;
        }

        return $row;
    }
    public function fetchIdByName($pageName) {
        $query = $this->select()
        				->from($this->_name, array('pageId'))
        				->where('pageName = ?', $pageName);
        return $this->fetchRow($query);
    }
    public function fetchPageNames()
    {
    	$query = $this->select()
    	->from($this->_name, array('pageName'));
    	return $this->fetchAll($query);
    }
    public function fetchSearchData()
    {
        // rewrite this into a join to support searching by author
        try {
                $select = $this->select()->from($this->_name, array('pageId', 'role', 'pageName', 'pageUrl', 'visibility', 'createdDate', 'pageText'));
                return $this->fetchAll($select);
        } catch (Exception $e) {
            $this->log->alert($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
        }

    }
	/* (non-PHPdoc)
     * @see Dxcore_Db_Table_Form_Interface::getForm()
     */
    public function getForm (Array $columns = null)
    {
        $columnTypes = array();
        $columns = array('pageId', 'pageName');
        $this->form = new Zend_Dojo_Form($this->_name);
        $this->formSchema = $this->getAdapter()->describeTable($this->_name);
        //Zend_Debug::dump(self::VAR_CHAR);
        if($columns !== null)
        {

        }
        return $this->form;
    }
	/* (non-PHPdoc)
     * @see Zend_Acl_Resource_Interface::getResourceId()
     */
    public function getResourceId ()
    {
        // TODO Auto-generated method stub
        return $this->_name;
    }
    public function getFormSchema()
    {
        return $this->formSchema;
    }

}
