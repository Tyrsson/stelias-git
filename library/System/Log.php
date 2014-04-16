<?php
require_once ('Zend/Log.php');
class System_Log extends Zend_Log
{
	const EMERG   = 0;  // Emergency: system is unusable
	const ALERT   = 1;  // Alert: action must be taken immediately
	const CRIT    = 2;  // Critical: critical conditions
	const ERR     = 3;  // Error: error conditions
	const WARN    = 4;  // Warning: warning conditions
	const NOTICE  = 5;  // Notice: normal but significant condition
	const INFO    = 6;  // Informational: informational messages
	const DEBUG   = 7;  // Debug: debug messages
    /**
     *
     * @var boolean
     */
    protected $_registeredErrorHandler = true;
    protected $_timestampFormat        = 'm-d-Y h:i:s';

    protected function _packEvent($message, $priority)
    {

    	return array_merge(array(
    			'timeStamp'    => date($this->_timestampFormat),
    			'message'      => $message,
    			'priority'     => $priority,
    			'priorityName' => $this->_priorities[$priority],
    	),
    			$this->_extras
    	);
    }

    public function addUserEvent(Zend_Auth $user = null, $name = null, $userId = null)
    {
		if($user !== null && $user->hasIdentity() )
        {
            $this->setEventItem('userId', $user->getIdentity()->userId);
            $this->setEventItem('userName', $user->getIdentity()->userName);
        }
        elseif($user == null && $name !== null) {
            $this->setEventItem('userId', 0);
            $this->setEventItem('userName', $name);
        }
    }
}