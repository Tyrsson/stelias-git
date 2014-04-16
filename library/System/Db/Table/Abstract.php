<?php

/**
 * Abstract
 *
 * @author Joey
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class System_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $log;

	public function setLog($log)
	{
		$this->log = $log;
	}
	public function getLog()
	{
		if(!isset($this->log))
		{
			$this->log = Zend_Registry::get('log');
		}
		return $this->log;
	}
}
