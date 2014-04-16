<?php
abstract class User_Service_UserAbstract
{
    public $auth;
    public $isLogged;
    /**
     *
     * @param Zend_Auth $auth
     */
    public function __construct ($auth = null)
    {
    	if(!$auth instanceof Zend_Auth) {
    		$auth = Zend_Auth::getInstance();
    	}
    	self::setAuth($auth);
    	$this->isLogged = self::checkSessionState();
    	self::init();
    }
    public function checkSessionState ()
    {
    	return $this->auth->hasIdentity();
    }
	/**
     * @return the $auth
     */
    public function getAuth ()
    {
        return $this->auth;
    }

	/**
     * @param field_type $auth
     */
    public function setAuth ($auth)
    {
        $this->auth = $auth;
    }

	/**
     * @return the $authAdapter
     */
    abstract protected function getAuthAdapter ();
}