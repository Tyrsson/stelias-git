<?php
/**
 * Rowset_AppSettings
 *
 * @author Joey Smith
 * @version 0.1
 */
class Admin_Model_Rowset_AppSettings extends Zend_Db_Table_Rowset_Abstract
{
	protected $_tableClass = 'Admin_Model_AppSettings';

	public function getData() {
		return $this->_data;
	}
}