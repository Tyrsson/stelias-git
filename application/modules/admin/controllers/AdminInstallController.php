<?php
/**
 * Admin_AdminInstallController
 * 
 * @author Joey Smith
 * @version 0.1
 */
require_once 'Zend/Controller/Action.php';
class Admin_AdminInstallController extends Zend_Controller_Action
{
    protected $_db;
    protected $_sqlPath;
    protected $fileName = 'dxcore.sql';
    public    $tempLine = '';
    public    $lines;
    private   $userName = 'dxadmin';
    private   $passWord = 'e1da551374f0a6f136916647ab0f157cc192ea22';
    
    public function init() {
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_sqlPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . $this->fileName;
        $this->_helper->layout->setLayout('install');
    }
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        $form = new User_Form_Login();
        
        if($this->_request->isPost()) {
            if($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                if($this->userName === $data['username'] && sha1($data['password']) === $this->passWord) {
                    $this->_redirect('/admin/install/main');
                } else {
                    die('User Authorization failed!');
                }
            }
        }
        $this->view->form = $form;
    }
    public function installAction() {
        
        // This must be set and it must be a string
        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        //Zend_Debug::dump($ref);
        // This must come from the install index action where you auth if not then we need to die now
        if($ref !==  'http://'.$_SERVER['SERVER_NAME'].'/admin/install') {
            //die('Incorrect referer');
        }
        
        $form = new Admin_Form_Install();
        
        if($this->_request->isPost() && ( isset($this->_request->proceed) && $this->_request->proceed == 1 ) ) {
	        $this->lines = file( $this->_sqlPath );
	        foreach($this->lines as $line) {
	            // Skip it if it's a comment
	            if (substr($line, 0, 2) == '--' || $line == '')
	                continue;
	        
	            // Add this line to the current segment
	            $this->tempLine .= $line;
	            // If it has a semicolon at the end, it's the end of the query
	            if (substr(trim($line), -1, 1) == ';')
	            {
	                try {
	                    $this->_db->getConnection()->exec($this->tempLine);
	                } catch (Exception $e) {
	                    echo 'Caught Exception: ' . $e->getMessage();
	                }
	                $this->tempLine = '';
	            }
	        }
	        foreach($this->_db->listTables() as $table) {
	            echo "Created Table: $table<br />";
	        }
        
        } else {
        	$this->view->form = $form;
        }
    }
}