<?php
/**
 * Pages_AdminPagesController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';
class Pages_AdminPagesController extends Dxcore_Controller_AdminAction
{
    public $page;
    protected $_pService;
    public $pageName;
    protected $pageUrlFilter;
    protected $pageNameFilter;
    protected $matches = array('/ /', '/_/', '/--/', '/&/', '/\'/', '/\"/');
    protected $replacement = array('-', '-', '-', 'and', '', '');
    protected $nameMatches = array('/&/');
    protected $nameReplacement = array('and');
    //public    $searchIndexPath;
    public function init()
    {
        parent::init();


        //$this->pages = new Pages_Model_Pages();

        $this->pageUrl = $this->_request->getParam('pageUrl', null);

        //$this->page = $this->pages->fetchByUrl($this->pageUrl);

        // create the filter chains
        $this->pageUrlFilter = new Zend_Filter();
        $this->pageNameFilter = new Zend_Filter();


        $this->entities = new Zend_Filter_HtmlEntities(array('quotestyle' => ENT_QUOTES));
        $this->trimFilter = new Zend_Filter_StringTrim();
        $this->alnumFilter = new Zend_Filter_Alnum(array('allowwhitespace' => true));
        $this->toLowerFilter = new Zend_Filter_StringToLower();

        $this->replaceUrlFilter = new Zend_Filter_PregReplace();
        $this->replaceUrlFilter->setMatchPattern($this->matches);
        $this->replaceUrlFilter->setReplacement($this->replacement);

        // build the chain
        $this->pageUrlFilter->addFilter($this->trimFilter);
        $this->pageUrlFilter->addFilter($this->toLowerFilter);
        $this->pageUrlFilter->addFilter($this->replaceUrlFilter);


        $this->pageNameFilter->addFilter($this->trimFilter);
        $this->replaceNameFilter = new Zend_Filter_PregReplace();
        $this->replaceNameFilter->setMatchPattern($this->nameMatches);
        $this->replaceNameFilter->setReplacement($this->nameReplacement);
        $this->pageNameFilter->addFilter($this->replaceNameFilter);
        $this->pageNameFilter->addFilter($this->alnumFilter);

        $this->roleTable = new User_Model_Roles();

        $this->validatePageName = new Zend_Validate_Db_NoRecordExists(array('table' => 'pages', 'field' => 'pageName'));

    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {

    }
    public function createAction()
    {
        $form = new Pages_Form_CreatePage();
        $form->setAction('/admin/pages/create');
        $page = new Pages_Model_Pages();
        $row = $page->fetchNew();
        $pageCount = $page->fetchTotalPageCount();
        //Zend_Debug::dump($row);
        try {
            switch($this->_request->isPost()) {
                case true :
                	//Zend_Debug::dump($this->_request->getPost());
                    if($form->isValid($this->_request->getPost())) {
                        $this->post = $form->getValues();
                            switch($page->allowCreateType($this->post['pageType'])) {

                                case true :

                                    $row->setFromArray($this->post);
                                    $row->role = $this->roleTable->fetchRoleById($this->post['role']);
                                    $pageName = $this->pageNameFilter->filter($this->post['pageName']);
                                    if($this->validatePageName->isValid($pageName))
                                    {
                                        $row->pageName = $pageName;
                                    } else {
                                        throw new Zend_Application_Exception(sprintf('A page with that name already exist, please choose another page name.', $this->post['pageName']));
                                    }
                                    $row->pageUrl = $this->pageUrlFilter->filter($this->post['pageName']);
                                    $date = Zend_Date::now();
                                    $row->createdDate = $date->getTimestamp();
                                    $row->pageOrder = ++$pageCount;
                                    $row->userId = $this->user->userId;
                                    $row->parentId = $this->post['parentId'];
                                    $row->showInHomeWidget = $this->post['showInHomeWidget'];
//                                     if(isset($this->post['parent']) && $this->post['parent'] !== '') {
//                                         $result = $page->fetchIdByName((int)$this->post['parent']);
//                                         $page->init($result->pageId);
//                                         $row->parentId = $result->pageId;
//                                     } else {
//                                         $row->parentId = 0;
//                                     }
                                    $id = $row->save();
                                    $ns = new Zend_Session_Namespace($this->module);
                                    $ns->pageId = $id;

                                    if($id > 0) {

                                        $this->messenger->addMessage('The page was successfully created and you will be redirected in 3 seconds.');
                                        $this->view->pageName = $row->pageUrl;
                                        //$this->redirect('/admin/success');
                                    } else {
                                        throw new Zend_Application_Exception('There was an unexpected exception while trying to complete your request!');
                                    }

                                break;
                                case false :
                                    throw new Zend_Application_Exception(sprintf('Maximum supported %s pages is 1, please choose another Page Type.', $this->post['pageType']));
                                    break;
                        } // end switch

                    }
                    break;
                case false :
                    $this->view->form = $form;
                    break;
            }
        } catch (Exception $e) {
            $this->log->alert($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
            echo $e->getMessage();
        }

    }

    public function editAction()
    {
        $form = new Pages_Form_EditPage();
        $form->setAction('/admin/pages/edit/'.$this->pageUrl);
        $model = new Pages_Model_Pages();
        $page = $model->fetchForEditByUrl($this->pageUrl);
        $pageList = $model->getPagesForOrder();
        $this->view->orderList = $pageList->toArray();
        $ns = new Zend_Session_Namespace($this->module);
        $ns->pageId = $page->pageId;
        $this->view->pageName = $page->pageName;

        switch($this->isAjax()) {
            case true :
                	if(isset($_POST['order'])) {
                	$i = 1;
        	        	foreach($_POST['order'] as $order) {
        	        		$orderParts = explode('_', $order);
        	        		$pageToOrderId = $orderParts[1];
        	        		$row = $model->fetchById($pageToOrderId);
        	        		$row->pageOrder = $i;
        	        		$row->save();
        	        		$i++;
        	        		continue;
        	        	}
                	}

                	$pageList = $model->getPagesForOrder();
                	$this->view->orderList = $pageList->toArray();
                	$this->getHelper('viewRenderer')->setNoRender(true);
                	$this->_helper->layout->disableLayout();
                	if(isset($this->_request->pageUrl)) {
                		$page = new Pages_Model_Pages();
                		$child = $page->fetchByUrl($this->_request->pageUrl);
                		//$this->_response->setBody(var_dump($_POST));
                	}
        	break;
        }

        switch($this->_request->isPost()) {
            case true :

                if($form->isValid($this->_request->getPost()))
                {
                    $this->post = $form->getValues($this->post);
                    //die(Zend_Debug::dump($this->post));
                    try {

                        switch($model->allowCreateType($this->post['pageType'])) {

                            case true :
                                //Zend_Debug::dump($this->post);
                                $page->setFromArray($this->post);
                                $page->role = $this->roleTable->fetchRoleById($this->post['role']);
                                $pageName = $this->post['pageName'];
                                $page->pageUrl = $this->pageUrlFilter->filter($this->post['pageName']);
                                $date = Zend_Date::now();
                                $page->modifiedDate = $date->getTimestamp();
                                $save = (bool) $page->save();
                                if($save) {

                                    $this->messenger->addMessage('Page successfully edited.');

                                    $this->redirect( '/'. $page->pageUrl);
                                } else {
                                    throw new Zend_Application_Exception('An unexpected error occured while trying to complete your request!');
                                }
                                break;

                            case false :
                                throw new Zend_Application_Exception(sprintf('Maximum supported %s pages is 1, please choose another Page Type.', $this->post['pageType']));
                                break;
                        }

                    } catch (Exception $e) {
                        $this->log->alert($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
                    }
                }

                break;
            case false :
                $data['pageId'] = $page->pageId;
                $data['parentId']  = $page->parentId;
                $data['role'] = $this->roleTable->fetchIdByRole($page->role);
                $data['pageName'] = $page->pageName;
                $data['visibility'] = $page->visibility;
                $data['userId'] = $this->user->userId;
                $data['showInHomeWidget'] = $page->showInHomeWidget;
                //$timestamp = (int) $page->createdDate;
                //$date = new Zend_Date($timestamp, Zend_Date::TIMESTAMP);
                //$today = $date->toString('MM/dd/yyyy');
                //$data['createDated'] = $today;
                $data['pageOrder'] = $page->pageOrder;
                $data['pageType'] = $page->pageType;
                $data['pageText'] = $page->pageText;
                $data['showSlider'] = $page->showSlider;
                $form->populate($data);

                break;
        }
        $this->view->form = $form;
    }
    public function deleteAction() {
        try {
            $this->_helper->viewRenderer->setNoRender(true);
            switch(isset($this->pageUrl)) {
                case true :
                    if($this->pageUrl !== 'home') {
                        $model = new Pages_Model_Pages();
                        $page = $model->fetchForEditByUrl($this->pageUrl);

                        $delete = (int) $page->delete();
                        if($delete > 0) {
                            $this->messenger->addMessage("$page->pageName was deleted successfully!");
                            //$this->view->pageName = '';
                            $this->redirect('/admin/success');
                        } else {
                            throw new Zend_Db_Exception(' unknown error trying to process request!');
                        }
                    }
                    break;

                case false :

                    break;

            }
        } catch (Exception $e) {
            $this->log->warn($e->getMessage() . ' ::Error Location:: File:: ' . $e->getFile() . ' :: Line:: ' . $e->getLine());
        }

    }
    public function successAction() {

        if(isset($this->_request->pageUrl)) {
            $this->view->pageName = $this->_request->pageUrl;
        }

    } // <- void method, here only for loading the view
}
