<?php
/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/**
 * jQuery Gallery View Helper
 *
 * @package    ZendX_JQuery
 * @subpackage View
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class System_View_Helper_GalleryContainer extends ZendX_JQuery_View_Helper_UiWidget
{
    /**
     * Create a jQuery UI Dialog filled with the given content
     *
     * @link   http://docs.jquery.com/UI/Dialog
     * @param  string $id
     * @param  string $content
     * @param  array $params
     * @param  array $attribs
     * @return string
     */
    public function galleryContainer($id, $params=array(), $attribs=array())
    {
    	$this->jquery->addStylesheet('/js-src/jquery-ui/plugins/css/jquery-ui-gallery.css');
    	$this->jquery->addJavascriptFile('/js-src/jquery-ui/plugins/load-image.js');
    	$this->jquery->addJavascriptFile('/js-src/jquery-ui/plugins/gallery.js');
        if (!array_key_exists('id', $attribs)) {
            $attribs['id'] = $id;
        }

        if(count($params) > 0) {
            $params = ZendX_JQuery::encodeJson($params);
        } else {
            $params = "{}";
        }

        $js = sprintf('%s("#%s").imagegallery(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
        );
        $this->jquery->addOnLoad($js);

        $html = '<div'
                . $this->_htmlAttribs($attribs)
                . '>'
                . '</div>';
        return $html;
    }
}
