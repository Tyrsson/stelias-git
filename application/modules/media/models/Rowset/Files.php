<?php
class Media_Model_Rowset_Files extends Zend_Db_Table_Rowset_Abstract
{
    protected $_tableClass = 'Media_Model_File';
    
    public function getData()
    {
        return $this->_data;
    }
}