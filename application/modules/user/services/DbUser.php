<?php
class User_Service_DbUser extends User_Service_UserAbstract implements Zend_Acl_Role_Interface
{
	public $db;
	public $role;
	public $authAdapter;
	private $userName;
	private $passWord;

	public function __construct($userName, $passWord)
	{
		$this->authAdapter = self::getAuthAdapter();
		if(null !== $userName) {
			self::setUserName($userName);
		}
		if (null !== $passWord) {
			self::setPassWord($passWord);
		}
		self::authenticate();

		return $this;
	}
	public function getRoleId()
	{

	}
	private function setUserName($userName)
	{
		$this->userName = $userName;
	}
	private function setPassWord($passWord)
	{
		$this->passWord = $passWord;
	}
	public function writeSession()
	{

	}
	public function authenticate()
	{
		$this->authAdapter->setIdentity($this->userName)->setCredential($this->passWord);
		return $this->auth->authenticate($this->authAdapter);
	}
    protected function getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        return new Zend_Auth_Adapter_DbTable($dbAdapter, 'user', 'userName', 'passWord', 'sha1(?) AND uStatus != "disabled"');
    }//////////////////////////////////////////////////////////////////
}