<?php
/**
 * MediaController
 * 
 * @author
 * @version 
 */
require_once 'Dxcore/Controller/Action.php';
class Media_MediaController extends Dxcore_Controller_Action
{
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        
    }
    public function testAction() {
		 $data = $this->_request->getParams();
		 $data = (object) $data;
		 $data->basePath = '/modules/media/images/';
		 
        $this->view->data = $data;
        
    }
}
