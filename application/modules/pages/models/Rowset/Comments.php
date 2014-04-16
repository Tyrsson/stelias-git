<?php
require_once ('Zend/Db/Table/Rowset/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * @package Pages
     */
class Pages_Model_Rowset_Comments extends Zend_Db_Table_Rowset_Abstract
{
    protected $_name = 'pageComments';
    protected $_sequence = true;
	/**
	 * @return array the comments in an array
	 */
	public function getAsArray()
	{
		$comments = array();
		while ($this->valid()) {
			$comment = $this->current();
			$comments[] = $comment->content;  // the actual tag name
			$this->next();
		}
		$this->rewind();
		return $comments;
	}
}
?>