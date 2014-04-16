<?php
/**
 * UserAdminController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class User_AdminUserController extends Dxcore_Controller_AdminAction
{
    public $context = array('rolestore' => array('json'));
    /**
     * The default action - show the home page
     */
	public function init()
	{
		// Call the parent init to make sure we have the parents properties.
		parent::init();
		if($this->_request->isXmlHttpRequest())
		{
		    if(isset($this->context[$this->_request->action]))
		    {
		        $this->_helper->contextSwitch()->initContext();
		        $this->_helper->layout->disableLayout();
		        $this->getHelper('viewRenderer')->setNoRender(true);
		    }
		}
	}
    public function indexAction ()
    {
        // TODO Auto-generated UserAdminController::indexAction() default action
        //echo 'path = /admin/user/index';
    }
    public function editAction ()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) 
        {
            $ident = Zend_Auth::getInstance()->getIdentity();
            
            $form = new User_Form_EditUser();
            $table = new User_Model_User();
            $row = $table->fetch($this->_request->id);
            $this->view->form = $form;
            
            if ($this->getRequest()->isPost()) 
            {
                $postData = $this->getRequest()->getPost();
                if ($form->isValid($postData)) 
                {
                    $values = $form->getValues();
                    $row->setFromArray($values);
                    
                    $id = (int) $row->save();
                    if($id > 0) {
                    	$this->_redirect('/admin/index');
                    }
                } 
             }
             else
             {
                    // pre-populate form
                    $this->view->form->populate($row->toArray());
             }    
        }
    }
    public function createAction()
    {
        $form = new User_Form_EditUser();
        $model = new User_Model_DbTable_User();
        
    }
    public function deleteAction()
    {
    	$table = new User_Model_User();
    	$id = $this->_request->getParam('id');
    	$page = $this->_request->getParam('page');
    	if(isset($id))
    	{
    		$row = $table->fetch($id);
    		$row->delete();
    		$this->_redirect('/admin/user/userlist/'. $page);
    	} else {
    		throw new Dxcore_Application_Exception('The user was not deleted please check the error log.');
    	}
    }
    public function userlistAction ()
    {
         //$page = $this->getRequest()->getParam('page');
         $page = $this->_request->getParam('page', 1);
         $this->view->page = $page;
         $table = new User_Model_User();

         $paginator = $table->getOnePage($page);
         $this->view->paginator = $paginator;
    }
    public function rolestoreAction()
    {
        if($this->_request->isXmlHttpRequest())
        {
	        $roles = new Zend_Db_Table('roles');
	        foreach( $roles->fetchAll( $roles->select( array('role')))->toArray() as $role)
	        {
	        	foreach($role as $r)
	        	{
	        	    $name[] = array('rName' => $r);
	        	}
	        }
	        $data = new Zend_Dojo_Data('rName', $name);
	        echo $data->toJson();
        } else {
            exit;
        }
    }
}
