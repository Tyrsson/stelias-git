<?php
require_once ('Zend/Db/Table/Row/Abstract.php');
    /**
     * @author Joey Smith
     * @version 0.9.1
     * @package Pages
     */
class Pages_Model_Row_Page extends Aurora_Model_Row_Searchable
{


    /**
     * Name of the class of the Zend_Db_Table_Abstract object.
     *
     * @var string
     */
    protected $_tableClass = 'Pages_Model_Pages';

    /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $_primary = 'pageId';
    private $comments = null;
	private $user = null;
	//protected $keyWords;
	protected $settings;

	public $log;

	public function init() {
	    parent::init();
	}
	public function getSearchIndexFields()
	{
	    $filter = new Zend_Filter_StripTags();
	    $fields['class'] = __CLASS__; // try changing this to get_called_class() to ident each rows class instead of the parent
	    $fields['key'] = $this->_data['pageId'];
	    $fields['docRef'] = $fields['class'].':'.$fields['key'];
	    $fields['url'] = '/'.$this->_data['pageUrl'];
	    $fields['title'] = $this->_data['pageName'];
	    $fields['contents'] = $this->_data['pageText'];
	    $fields['summary'] = substr($filter->filter($this->_data['pageText']), 0, 300);
	    $fields['createdBy'] = $this->_data['userId'];
	    $fields['dateCreated'] = $this->_data['createdDate'];

	    return $fields;
	}

	/**
	 * @return Model_Row_User
	 */
	public function getUser()
	{
		if (!$this->user) {
			$this->user = $this->findParentRow('User_Model_Users');
		}

		return $this->user;
	}

	/**
	 * Allows post-insert logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
// 	protected function _postInsert()
// 	{
// 	    //parent::_postInsert();
// 		$lookup = new Pages_Model_PageLookup();
// 		$row = $lookup->fetchNew();
// 		$row->setFromArray($this->_data);
// 		$row->userId = Zend_Auth::getInstance()->getIdentity()->userId;
// 		$table = $this->getTableClass();
// 		if(isset($table->parentId))
// 		{
// 		    $row->parentId = $table->parentId;
// 		}
// 		$row->save();
// 		//parent::_postInsert();
// 	}
}