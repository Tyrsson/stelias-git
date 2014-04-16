<?php
/**
 * Aurora CMS
 *
 * LICENSE
 *
 * @category   Zend
 * @package    Zend_Dojo
 * @subpackage View
 * @copyright  Copyright (c) 2011-2013 Webinertia
 * @license    http://webinertia.net/license.txt
 * @version    1.0
 */

/** Zend_Dojo_View_Helper_DijitContainer */
require_once 'Zend/Dojo/View/Helper/DijitContainer.php';

/**
 * Dojo BorderContainer dijit
 *
 * @uses       Zend_Dojo_View_Helper_DijitContainer
 * @package    Zend_Dojo
 * @subpackage View
 * @copyright  Copyright (c) 2011-2013 Webinertia
 * @license    http://webinertia.net/license.txt
  */
class Zend_Dojo_View_Helper_BorderContainer extends Zend_Dojo_View_Helper_DijitContainer
{
    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit  = 'dijit/layout/BorderContainer';

    /**
     * Dojo module to use
     * @var string
     */
    protected $_module = 'dijit/layout/BorderContainer';

    /**
     * Ensure style is only registered once
     * @var bool
     */
    protected $_styleIsRegistered = false;

    /**
     * dijit.layout.BorderContainer
     *
     * @param  string $id
     * @param  string $content
     * @param  array $params  Parameters to use for dijit creation
     * @param  array $attribs HTML attributes
     * @return string
     */
    public function borderContainer($id = null, $content = '', array $params = array(), array $attribs = array())
    {
        if (0 === func_num_args()) {
            return $this;
        }

        // this will ensure that the border container is viewable:
        if (!$this->_styleIsRegistered) {
            $this->view->headStyle()->appendStyle('html, body { height: 100%; width: 100%; margin: 0; padding: 0; }');
            $this->_styleIsRegistered = true;
        }

        // and now we create it:
        return $this->_createLayoutContainer($id, $content, $params, $attribs);
    }
}
