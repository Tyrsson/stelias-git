<?php

/**
 * Counters
 *
 * @author jsmith
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_Counters extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'counters';
	protected $_primary = 'name';
	protected $_sequence = false;


	public function add($name, $count = null)
	{
		$q = $this->select()->from($this->_name, array('name', 'count'))->where('name = ?', $name);
		$row = $this->fetchRow($q);
		if($count == null) {
			$row->count++;
		}
		elseif(is_int($count)) {
			$row->count += $count;
		}
		elseif(is_string($count)) {
			$row->count += (int)$count;
		}
		$row->save();
	}
	public function fetch($name) {
		$q = $this->select()->from($this->_name, array('name', 'count'))->where('name = ?', $name);
		$row = $this->fetchRow($q);
		return $row->count;
	}
}
