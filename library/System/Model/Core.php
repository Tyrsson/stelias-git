<?php

/**
 * SystemCore
 *
 * @author Joey
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class System_Model_Core extends Zend_Db_Table_Abstract implements System_Model_DbTable_SystemInterface
{
	public $log;
	/* (non-PHPdoc)
	 * @see System_Model_DbTable_SystemInterface::fetchDropDown()
	 */
	public function init() {
		try {
			switch(Zend_Registry::isRegistered('log')) {
				case true :
					$this->log = Zend_Registry::get('log');
					break;
				case false :
					// will hold calling a none default logger, and will be expanded to lazy load.
					break;
			}
		} catch (Exception $e) {
			echo $e->getMessage() . ' :: ' . $e->getFile() . ' :: ' . $e->getLine();
		}
	}
	public function fetchAutoInc ()
	{
	    //$sql = 'SHOW TABLE STATUS LIKE ' . $this->_name . ';';
	    //$result = $this->_db->query("SHOW TABLE STATUS LIKE $this->_name;");
	   
	    
	    $sql = 'SHOW TABLE STATUS LIKE ' . $this->_db->quote($this->_name, 'string');
	    //$result = $this->_db->getConnection()->exec($sql);
	    $result = $this->_db->fetchAll($sql);
	    Zend_Debug::dump($result[0]['Auto_increment']);
	}
	/*
	 * @var $valueColumn The column name for the Label value
	 */
	public function fetchDropDown($valueColumn, $withDefault = false, $defaultId = 0, $defaultLabel = 'None', $asArray = true) {

		try {
			$q = $this->select()->from($this->_name, array('key' => $this->getPrimaryKey(), 'value' => "$valueColumn"));

			$result = $this->fetchAll($q)->toArray();
			//Zend_Debug::dump($result);
			if($withDefault) {
				array_unshift($result, array('key' => $defaultId, 'value' => $defaultLabel));
			}

			if($result == null) {
				$result = array(0 => 'No categories found');
			}
			return $result;

			//Zend_Debug::dump($result);

		} catch (Zend_Exception $e) {
			$this->log->crit($e);
			echo $e->getMessage();
		}


	}
	public function getPrimaryKey()
	{
		switch(is_array($this->_primary)) {
			case true :
				return implode($this->_primary);
				break;
			case false :
				return $this->_primary;
				break;
		}

	}

}
