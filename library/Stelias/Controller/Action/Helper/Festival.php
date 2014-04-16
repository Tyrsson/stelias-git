<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Festival Action Helper
 *
 * @uses actionHelper Stelias_Controller_Action_Helper
 */
class Stelias_Controller_Action_Helper_Festival extends Dxcore_Controller_Action_Helper_Widget
{
	/* (non-PHPdoc)
     * @see Dxcore_Controller_Action_Helper_Widget::buildWidget()
     */
    public function buildWidget ()
    {
        $data = array(1, 2, 3);
        $this->renderWidget($data, 'festival');
    }

    
}
