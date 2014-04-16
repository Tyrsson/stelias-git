<?php

/**
 * AjaxController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Pages_AjaxController extends Dxcore_Controller_Action
{
    public $context = array(
            'content' => array(
                    'ajax',
                    'json',
                    'xml'
            )
    );
    public $pModel;
    public $children;
    public $parent;
    public $pUrl;
    
    public function init() {
        parent::init();
        $this->pModel = new Pages_Model_Pages();
        try {
            
        } catch (Exception $e) {
        }
    }
    public function contentAction()
    {
        switch($this->isAjax()) {
            case true :
                
                break;
                
            case false :
                
                break;
        }
    }
}
