<?php
class System_ErrorLog extends Zend_Log {
	const EMERG = 0; // Emergency: system is unusable
	const ALERT = 1; // Alert: action must be taken immediately
	const CRIT = 2; // Critical: critical conditions
	const ERR = 3; // Error: error conditions
	const WARN = 4; // Warning: warning conditions
	const NOTICE = 5; // Notice: normal but significant condition
	const INFO = 6; // Informational: informational messages
	const DEBUG = 7; // Debug: debug messages
	const FILE_DL = 8;
	/**
	 *
	 * @var boolean
	 */
	//protected $_registeredErrorHandler = true;
	protected $_timestampFormat = 'm-d-Y h:i:s';
	protected function _packEvent($message, $priority) {
		return array_merge(array('timeStamp' => date($this->_timestampFormat), 'message' => $message, 'priority' => $priority, 'priorityName' => $this->_priorities[$priority]), $this->_extras);
	}

}
