<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * ChildPages helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_ChildPages extends System_View_Helper_Abstract
{

    public function childPages ($pageUrl)
    {
    	$model = new Page_Model_pages();

    	$this->html .='';
    }

}
