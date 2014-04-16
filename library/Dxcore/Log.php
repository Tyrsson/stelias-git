<?php
require_once ('Zend/Log.php');
class Dxcore_Log extends Zend_Log
{
	const EMERG   = 0;  // Emergency: system is unusable
	const ALERT   = 1;  // Alert: action must be taken immediately
	const CRIT    = 2;  // Critical: critical conditions
	const ERR     = 3;  // Error: error conditions
	const WARN    = 4;  // Warning: warning conditions
	const NOTICE  = 5;  // Notice: normal but significant condition
	const INFO    = 6;  // Informational: informational messages
	const DEBUG   = 7;  // Debug: debug messages
    const FILE_DL  = 8;
    /**
     *
     * @var boolean
     */
    protected $_registeredErrorHandler = true;
    protected $_timestampFormat        = 'm-d-Y h:i:s';
    public    $fileId;
    protected function _packEvent($message, $priority, $fileId = null)
    {
    	return array_merge(array(
    			'timeStamp'    => date($this->_timestampFormat),
    			'message'      => $message,
    			'priority'     => $priority,
    			'priorityName' => $this->_priorities[$priority],
    			//'fileId'      => $fileId
    	),
    			$this->_extras
    	);
    }

//     public function setFileId($fileId)
//     {
//     	$this->fileId = $fileId;
//     }
//     public function addUserEvent($fileId = null)
//     {
//     	$userManager = new User_Model_UserManager();
    	
//     	if($fileId != null)
//     	{
//     		$this->setFileId($fileId);
//     	}
//     	//Zend_Debug::dump($this->_extras, 'before setitem', true);
//         if( Zend_Auth::getInstance()->hasIdentity() )
//         {
//             $this->setEventItem('userId', isset($userManager->userId) ? $userManager->userId : 0);
//             $this->setEventItem('userName', isset($userManager->userName) ? $userManager->userName : 'guest');
//             $this->setEventItem('fileId', $this->fileId);
//         } 
//         else {
//             $this->setEventItem('userId', 0);
//             $this->setEventItem('userName', '');
//             $this->setEventItem('fileId', $this->fileId);
//         }  
         
//     }
}
?>