<?php
/**
 * RegisterController
 * 
 * @author 
 * @version 
 */
require_once 'Dxcore/Controller/Action.php';
class User_UserRegisterController extends Dxcore_Controller_Action
{

	/**
	 * $newUser empty zend db table row object
	 * @var object Zend_Db_Table_Row
	 */
	public $newUser;
	/**
	 * $validator 
	 * @var object Zend_Validate_Db_NoRecordExists
	 */
	public $validator;
	/**
	 * $form
	 * @var object Zend_Form
	 */
	public $form;
	/**
	 * $mailer
	 * @var object Zend_mail
	 */
	public $mailer;

	/**
	 * $emailValidator
	 * @var Zend_Validate_EmailAddress
	 */
	public $emailValidator;
	
	/** 
	 * $date 
	 * @var $date Zend_Date
	 */
	public $date;
	/**
	 * $hash 
	 * @var string 
	 */
	 private $hash;
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::preDispatch()
	 */
    public function preDispatch ()
    {
        //Zend_Debug::dump($this->appSettings);
        switch($this->appSettings->enableRegistration) {
            case false :
                $this->redirect('/');
                break;
            default:
                break;
        }
    }
    public function init() {
    	
        parent::init();
        // create a unique hash for the user
        $this->hash = md5(uniqid(rand(), true));
        // get date as timestamp
        $this->date = new Zend_Date(Zend_Date::now(), Zend_Date::TIMESTAMP);
        //Zend_Debug::dump($this->date);
        // get the mailer
        $this->mailer = new Zend_Mail();
        // get the email validator
        $this->emailValidator = new Zend_Validate_EmailAddress(array(
            											'allow' => Zend_Validate_Hostname::ALLOW_DNS,
            											'mx'    => true
        												));
        // get the user manager
        $this->user = new User_Model_User();
        // get an empty row from the user manager in case we want to save a new user
        $this->newUser = $this->user->fetchNew();
        // get the registration form
        $this->form = new User_Form_Register();
        // get the validator
        $this->validator = new Zend_Validate_Db_NoRecordExists($options = array(
    	        															'adapter' => Zend_Db_Table::getDefaultAdapter(),
    	        															'table' => $this->user->getTableName(),
    	        															'field' => 'userName'
    	        															));
        
    }
    /**
     * The default action - registration form
     */
    public function indexAction ()
    {
    	
        //check we have post data
        if ($this->_request->isPost()) 
        {
            //check for valid form data....
            if ($this->form->isValid($this->_request->getPost())) 
            {
                //get the array of form data
                $values = $this->form->getValues();
                
                if (isset($values['userName']) && isset($values['passWord'])) 
                {
                	if($this->validator->isValid($values['userName'])) {
                		
                	    $date = new Zend_Date(Zend_Date::now(), Zend_Date::TIMESTAMP);
                	   // echo $date;
                	    $timestamp = $date->getTimestamp();
                	    
                	    
                	    //Zend_Debug::dump($timestamp);
                		// at this point we should not have a duplicate userName
                		#Since $this->newUser->userId = null we do not need to set this
                		$this->newUser->userName = $values['userName'];
                		$this->newUser->firstName = $values['firstName'];
                		$this->newUser->lastName = $values['lastName'];
                		$this->newUser->email    = $values['email'];
                		$this->newUser->passWord = sha1($values['passWord']);
                		//$this->newUser->companyName = '';
                		$this->newUser->registeredDate = $timestamp;
                		$this->newUser->hash = $timestamp;
                		$id = $this->newUser->save();
                		
                		$message = '';
                		$sitename = $_SERVER["SERVER_NAME"];
                		# Now we have an id, so we can build the verification link
                		$link = $this->scheme.'://'.$this->host.'/user/register/verify/' . $id . '/' . $timestamp;
                		
                		$message .= "Thank you for registering for an account at $sitename.\nPlease click the link below to activate your account \n$link"; 
                		
                		$this->mailer->setBodyText ($message);
                		$this->mailer->setFrom ( 'registrations@' . $sitename, $sitename . ' Registration' );
                		$this->mailer->addTo ( $values['email'] );
                		$this->mailer->setSubject ( 'Registration Verification' );
                		$this->mailer->send ();
                		
                		$this->messenger->addMessage('Thank you for registering, you will receive a verification email shortly. Please check your spam folder.');
                		$this->_redirect('/user/register/success');
                		
                	} else {
                		// we need to notify them they need to choose another userName since that one is in use
                		$this->messenger->addMessage('UserName already in use!!');
                		unset($values['userName']);
                		unset($values['passWord']);
                		$this->form->populate($values);
                	}
                }
            }
        }
        //attach it to the view
        $this->view->form = $this->form;
    }
    public function verifyAction() {
        
        if(isset($this->_request->uid) && isset($this->_request->hash)) 
        {
        	$requestHash = (int) $this->_request->hash;
        	
            // get the user manager
            $user = new User_Model_User();
            // get the user requested in the request
            $row = $user->fetch((int)$this->_request->uid);
            $storedHash = (int) $row->hash;

            if( $row instanceof User_Model_Row_User ) 
            {
                    if($row->uStatus === 'disabled') 
                    {
                        // we have a match on the hash so they clicked the email
                        if( isset($row->userId) && $row->userId > 0 && $row->userId !== null && $row->uStatus === 'disabled')
                        {
                            if( ($this->_request->uid === $row->userId) && ( $storedHash === $requestHash) )
                            {
                        		// ok so we have a matched hash, the time limit has not expired and the userId and uid match, so we got to let'em in
                        		$row->uStatus = 'enabled';
                        		$row->hash    = 0;
                        		$result = (int) $row->save();
                        		
                        		if($result === (int)$this->_request->uid) 
                        		{
                        		    $this->messenger->addMessage('Your account has been verified. You can now login!');
                        		}
                            } else {
                            	throw new Zend_Application_Exception('You can not verify another users account!');
                            }
                        }
                    } 
                    else {
                    $this->messenger->addMessage('Your account has already been enabled');
                    }
            } else {
                $this->messenger->addMessage('Your account has been deleted by an administrator.');
            }
        } 
        elseif(!isset($this->_request->uid)) {
            throw new Zend_Application_Exception('UID not found');
        }
        elseif(!isset($this->_request->hash)) {
            throw new Zend_Application_Exception('You must click on a valid email link to verify your account!');
        }
    }
    public function successAction () {}
}