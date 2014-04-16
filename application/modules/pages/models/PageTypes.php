<?php
/**
 * PageTypes
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';
class Pages_Model_PageTypes extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    const TYPE = 'type';
    protected $_name = 'pagetypes';
    protected $_primary = 'pageTypeId';
    protected $_sequence = true;


    public function fetchDropDown() {
        $query = $this->select()
        ->from($this->_name, array('key' => 'type', 'value' => 'type'));
        $result = $this->fetchAll($query)->toArray();
        return  array_reverse($result);

    }
}
