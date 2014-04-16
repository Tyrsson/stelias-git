<?php

/**
 * AdminController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Festival_AdminController extends Dxcore_Controller_AdminAction
{
    public $context = array('additems' => array('ajax'));
    public $menu;
    public $items;
    public $menuId;
    public $menuName;
    public $itemId;

    protected $sa;
    protected $cmd = null;
    private $_exit = true;
    private $_exitSA = true;
    public function preDispatch()
    {
        $this->menu = new Festival_Model_Menu();
        $this->items = new Festival_Model_MenuItems();
        $this->menuId = $this->_request->getParam('menuId', 1);
        $this->menuName = $this->_request->getParam('menuName', 0);
        $this->itemId = $this->_request->getParam('itemId', 0);
        $this->sa = $this->_request->getParam('sa', 'exit');
        $this->cmd = $this->_request->getParam('cmd', 'exit');
        if($this->sa !== 'exit') {
            $this->_exitSA = false;
        }
        if($this->cmd !== 'exit') {
            $this->_exit = false;
        }
    }
    public function init()
    {
        parent::init();
        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('additems', 'html')
             ->addActionContext('manageitems', 'html')
             ->initContext();
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        $menuList = $this->menu->fetchMenuList();
        //Zend_Debug::dump($menuList);
        $this->view->menuList = $menuList;
    }
    public function editMenuAction()
    {
        $form = new Festival_Form_CreateMenu();
        $hidden = new Zend_Form_Element_Hidden('menuId');
        $form->addElement($hidden);
        $menu = $this->menu->fetch($this->menuId);
        $date = new Zend_Date($menu->date, Zend_Date::TIMESTAMP);
        
        switch($this->_request->isPost()) {
            case true :
                if($form->isValid($this->_request->getPost())) {
                    $data = $form->getValues();
                    
                    $parts = explode('/', $data['date']);
                    //Zend_Debug::dump($parts);
                    $date = new Zend_Date(array('month' => $parts[0], 'day' => $parts[1], 'year' => $parts[2]));
                    $data['date'] = $date->getTimestamp();
                    
                    $menu->setFromArray($data);
                    $menu->save();
                    $this->redirect('/admin/festival');
                }
                break;
            case false :
                $menu->date = $date->toString('MM/dd/yyyy');
                //$menu->timestamp = '03/11/2013';
                //Zend_Debug::dump($menu->toArray());
                $form->populate($menu->toArray());
                //$dp = $form->getElement('')
                break;
        }
        
        $this->view->form = $form;
    }
    public function deleteMenuAction()
    {
        // prevents the helper from trying to load a view file that does not exist
        $this->_helper->viewRenderer->setNoRender();
        if($this->menuId !== 0)
        {
            $menu = $this->menu->fetch($this->menuId);
            $items = $this->items->fetchItems($this->menuId);
            if($items instanceof Zend_Db_Table_Rowset)
            {
                foreach($items as $item) {
                    $item->delete();
                }
            }
            elseif($items instanceof Zend_Db_Table_Row)
            {
                $items->delete();
            }
            $result = $menu->delete();
            if($result > 0) {
                $this->redirect('/admin/festival');
            }
        }
    }
    public function deleteItemAction()
    {
        // prevents the helper from trying to load a view file that does not exist
        $this->_helper->viewRenderer->setNoRender();
        $item = $this->items->fetchItemForManagement($this->menuId, $this->itemId);
        if($item instanceof Zend_Db_Table_Row) {
            $item->delete();
            $this->redirect('/admin/festival/manageitems/'. $this->menuId);
        }
        
    }
    public function createmenuAction()
    {
        //$this->view->menuId = 1;

        $form = new Festival_Form_CreateMenu();
        switch ($this->_request->isPost()) {
            case true :

                if($form->isValid($this->_request->getPost()))
                {
                    $row = $this->menu->fetchNew();
                    $data = $form->getValues();
                    //Zend_Debug::dump($data);
                    if(isset($data['date']) && !empty($data['date'])) {
                        $parts = explode('/', $data['date']);
                        //Zend_Debug::dump($parts);
                        $date = new Zend_Date(array('month' => $parts[0], 'day' => $parts[1], 'year' => $parts[2]));
                        $data['timestamp'] = $date->getTimestamp();
                        $row->setFromArray($data);
                        $result = $row->save();
                        if($result > 0) {
                            //$this->forward('manageitems', null, null, array('menuId' => $result, 'menuName' => $row->name));
                            $this->redirect('/admin/festival');
                        }
                    }
                }

                break;
            case false :

                break;
        }

        switch ($this->isAjax()) {
            case true :

                break;

            case false :

                break;
        }

        $this->view->form = $form;
    }
    public function manageitemsAction()
    {

        $form = new Festival_Form_MenuItems();
        $this->view->cmd = 'save';
        $preloadData = array('cmd' => 'save', 'menuId' => $this->menuId, 'pickupOnly' => 1);
        $menuName = $this->menu->fetchNameById($this->menuId);
        //Zend_Debug::dump($menuName->name);
        $this->view->menuName = $menuName->name;
        
        
        switch($this->_request->isPost()) {
            case true :

                    switch($this->cmd) {
                        case 'save' :
                            $this->view->cmd = 'save';
                            $item = $this->items->fetchNew();
                            $data = $this->_request->getPost();
                            $item->setFromArray($data);
                            $item->id = null;
                            $result = $item->save();
                            if($result > 0) {
                                $this->messenger->addMessage('Item successfully created');
                            } else {
                                $this->messenger->addMessage('Error');
                            }
                            $this->view->currentItems = $this->items->fetchItems($this->menuId);
                            break;

                    }

                break;

            case false :

                break;
        }
        $this->view->currentItems = $this->items->fetchItems($this->menuId);
        
        
        $this->view->menuId = $this->menuId;

        $form->populate($preloadData);
        $this->view->form = $form;
    }
    public function editItemAction()
    {
        $form = new Festival_Form_MenuItems();
        $cmd = $form->getElement('cmd');
        $cmd->setValue('edit');
        switch($this->_request->isPost()) {
            case true :
                if($form->isValid($this->_request->getPost()))
                {
                    $data = $form->getValues($this->_request->getPost());
                    //Zend_Debug::dump($data);
                    $item = $this->items->fetchItemForManagement($this->menuId, $this->itemId);
                    $item->setFromArray($data);
                    $result = $item->save();
                    if($result > 0) {
                        $this->messenger->addMessage('Item updated');
                        
                    } else {
                        $this->messenger->addMessage('Update Error');
                    }
                    $this->redirect('/admin/festival/manageitems/'.$this->menuId);
                }
                break;
                
            case false :
            default:
                $item = $this->items->fetchItemForManagement($this->menuId, $this->itemId);
                $form->populate($item->toArray());
                break;
        }
        $this->view->form = $form;
    }

}
