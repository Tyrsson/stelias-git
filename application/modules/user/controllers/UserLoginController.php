<?php
class User_UserLoginController extends Dxcore_Controller_Action {

    //public $user;
    public $userData;
    public $userName;
    public $role;

    public function preDispatch ()
    {
        //Zend_Debug::dump($this->appSettings);
        switch($this->appSettings->enableUserLogin) {
            case false :
                $this->redirect('/');
                break;
            default:
                break;
        }
    }
    public function init()
    {
    	parent::init();
    	//Zend_Debug::dump(sha1('direx10'));
    }//////////////////////////////////////////////////////////
    public function indexAction()
    {// login action
        if ($this->isLogged)
        {
            $this->_redirect('/user/account/summary'); // Already authenticated? Navigate away
        }
        $form = new User_Form_Login;
        $this->view->form = $form;
        switch($this->_request->isPost()) {
            case true :
                    try {
                        if ($form->isValid($this->_request->getPost()))
                        {
                            $values = $form->getValues();
                            $authAdapter = $this->getAuthAdapter();
                            // get the username and password from the form
                            $username = $values['username'];
                            $password = $values['password'];
                            // pass to the adapter the submitted username and password
                            $authAdapter->setIdentity($username)->setCredential($password);
                        
                            $result = $this->auth->authenticate($authAdapter);
                            
                            switch($result->isValid()) {
                                case true :
                                    if ($result->isValid()) {
                                    Zend_Session::regenerateId();
                                    $authStorage = $this->auth->getStorage();
                                    $userInfo = $authAdapter->getResultRowObject(array('userId','userName','role'));
                                    // persist the identity to storage
                                    $authStorage->write($userInfo);
                                    $this->_helper->getHelper('FlashMessenger')->addMessage('You were sucessfully logged in as&nbsp;' . $userInfo->userName);
                                    
                                        if (isset($userInfo->role)) {
                                            
                                            switch ($userInfo->role) {
                                                case "dxadmin":
                                                    $this->_redirect('/admin/index');
                                                    break;
                                                case "admin":
                                                    $this->_redirect('/admin/index');
                                                    break;
                                                case "mod":
                                                    $this->_redirect('/');
                                                    break;
                                                case "user":
                                                    $this->_redirect('/');
                                                    break;
                                            }
                                        }
                                    } 
                                    break;
                                case false :
                                    echo implode($result->getMessages());
                                    break;
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                break;
            case false :
                
                break;
        }
    }////////////////////////////////////////////////////////////////////
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/');
    }///////////////////////////////////////////////////////////////////
    public function devloginAction() {

        //$authStorage = $this->auth->getStorage();
        //$userInfo = $authAdapter->getResultRowObject(array('userId', 'userName', 'role'));
        //$authStorage->write($userInfo);

        $form = new User_Form_Login();

        if($this->_request->isPost()) {
            if($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();

//                 $options[] = array('host' => 'webserver.loc',
// 			                //'username' => $data['username'],
// 			                //'password' => $data['password'],
// 			                'accountDomainName' => 'webserver.loc',
// 			                'accountDomainNameShort' => 'WEBSERVER',
// 			                'accountCanonicalForm' => '3',
// 			                'baseDn' => 'DC=webserver,DC=loc',
// 			                'bindRequiresDn' => true,
// 			                'useSsl' => false,);

                $options[] = array('host' => 'server.dirextion.net',
                //'username' => $data['username'],
                //'password' => $data['password'],
                'accountDomainName' => 'dirextion.net',
                'accountDomainNameShort' => 'DIREXTION',
                'accountCanonicalForm' => '3',
                'baseDn' => 'DC=server,DC=dirextion,DC=net',
                'bindRequiresDn' => true,
                'useSsl' => false,);


                $adapter = new Zend_Auth_Adapter_Ldap($options, $data['username'], $data['password']);
                $result = $this->auth->authenticate($adapter);
                //Zend_Debug::dump($this->auth->authenticate($adapter));
                //exit();
                //Zend_Debug::dump($adapter->authenticate());
                //$result = $adapter->authenticate();
                if($result->isValid()) {
                    $this->_redirect('/admin/settings');
                    Zend_Debug::dump($result);
                } else {
                    Zend_Debug::dump($result);
                    die('you were NOT logged in via ldap');
                }
            }
        }

        $this->view->form = $form;
    }
    public function getLdapAuthAdapter()
    {

    }
    protected function getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'user', 'userName', 'passWord', 'sha1(?) AND uStatus != "disabled"');
        return $authAdapter;
    }///////////////////////////////////////////////////////////////////
    public function successAction()
    {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages())
        {
            $this->view->messages = $this->_helper
                            ->getHelper('FlashMessenger')
                            ->getMessages();
        }
        else {
            $this->_redirect('/user/login/success');
        }
    }////////////////////////////////////////////////////////////////////
}