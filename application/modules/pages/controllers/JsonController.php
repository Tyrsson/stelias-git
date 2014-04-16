<?php

/**
 * JsonController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Pages_JsonController extends Dxcore_Controller_AdminAction
{

    public $context = array(
            'pagenamestore' => array(
                    'json'
            ),
            'rolestore' => array(
                    'json'
            ),
            'quickpageedit' => array(
                    'ajax',
                    'json'
            ),
            'subpagecontent' => array(
                    'ajax',
                    'json'
            )
    );

    private $_roles;

    /**
     * The default action - show the home page
     */
    public function init ()
    {
        $this->_roles = new User_Model_Roles();
        
        $this->_helper->contextSwitch()->initContext();
        $this->_helper->layout->disableLayout();
    }

    public function rolestoreAction ()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $roles = $this->_roles->fetchRoles();
        
        foreach ($roles->toArray() as $r) {
            foreach ($r as $name) {
                $entries[] = array(
                        'role' => $name
                );
            }
        }
        
        $data = new Zend_Dojo_Data('role', $entries);
        echo $data->toJson();
    }

    public function pagenamestoreAction ()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $pages = new Pages_Model_Pages();
        $pageNames = $pages->fetchPageNames();
        
        foreach ($pageNames->toArray() as $n) {
            foreach ($n as $name) {
                $entries[] = array(
                        'name' => $name
                );
            }
        }
        
        $data = new Zend_Dojo_Data('name', $entries);
        echo $data->toJson();
    }

    public function quickpageeditAction ()
    {
        $pages = new Pages_Model_Pages();
        
        $row = $pages->fetchById($this->_request->pageId);
        $row->setFromArray($this->_request->getParams());
        $row->save();
        $this->_response->setBody(new Zend_Dojo_Data('page', $row->toArray()));
    }

    public function subpagecontentAction ()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->_helper->layout->disableLayout();
        if ($this->_request->isXmlHttpRequest()) {
            if (isset($this->_request->pageName)) {
                $page = new Pages_Model_Pages();
                $child = $page->fetchByName($this->_request->pageName);
                $this->_response->setBody(
                        '<h2>' . $child->pageName . '</h2><p>' . $child->pageText .
                                 '</p>');
            }
        }
    }
}
