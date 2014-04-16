<?php
require_once ('Zend/Db/Table/Rowset/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * 
     */
class Pages_Model_Rowset_Files extends Zend_Db_Table_Rowset_Abstract
{
        /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $_primary = 'fileId';
    protected $_sequence = true;
    
    public function getAsArray()
    {
    	$files = array();
    	while ($this->valid()) {
    		$file = $this->current();
    		$files[] = $file->fileName;  // the actual tag name
    		$this->next();
    	}
    	$this->rewind();
    	return $files;
    }
}
?>