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

/** Zend_Dojo_View_Helper_Dijit */
require_once 'Zend/Dojo/View/Helper/Dijit.php';

/**
 * Dojo Form dijit
 *
 * @uses       Zend_Dojo_View_Helper_Dijit
 * @package    Zend_Dojo
 * @subpackage View
 * @copyright  Copyright (c) 2011-2013 Webinertia
 * @license    http://webinertia.net/license.txt
  */
class Zend_Dojo_View_Helper_Form extends Zend_Dojo_View_Helper_Dijit
{
    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit  = 'dijit/form/Form';

    /**
     * Module being used
     * @var string
     */
    protected $_module = 'dijit/form/Form';

    /**
     * @var Zend_View_Helper_Form
     */
    protected $_helper;

    /**
     * dijit/form/Form
     *
     * @param  string $id
     * @param  null|array $attribs HTML attributes
     * @param  false|string $content
     * @return string
     */
    public function form($id, $attribs = null, $content = false)
    {
        if (!is_array($attribs)) {
            $attribs = (array) $attribs;
        }
        if (array_key_exists('id', $attribs)) {
            $attribs['name'] = $id;
        } else {
            $attribs['id'] = $id;
        }

        $attribs = $this->_prepareDijit($attribs, array(), 'layout');

        return $this->getFormHelper()->form($id, $attribs, $content);
    }

    /**
     * Get standard form helper
     *
     * @return Zend_View_Helper_Form
     */
    public function getFormHelper()
    {
        if (null === $this->_helper) {
            require_once 'Zend/View/Helper/Form.php';
            $this->_helper = new Zend_View_Helper_Form;
            $this->_helper->setView($this->view);
        }
        return $this->_helper;
    }
}
