<?php
class Dxcore_Controller_AdminAction extends Dxcore_Controller_Action
{
    public $isAdmin = true;
	public function init()
	{
		parent::init();
		$this->view->isAdmin = $this->isAdmin;
		$this->_helper->layout->setLayout ( 'admin' );

		if(!$this->isLogged) {
			$this->_redirect('/user/login');
		}
		if(!$this->acl->isAllowed($this->user->role, 'admin:area', 'admin.base')) {
			$this->messenger->addMessage('You do not have enough privileges to view this area!');
			$this->_redirect('/');
		}

		switch ($this->isAjax()) {
			case true :
				//$this->_helper->layout->disableLayout();
				//die('is ajax');
				break;
			case false :
				break;
		}

	}
}