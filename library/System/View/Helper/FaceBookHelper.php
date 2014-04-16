<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';
/**
 * SeoHelper helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class System_View_Helper_FaceBookHelper extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     * @var object appSettings
     */
    public $appSettings;
    /**
     *
     */
    public $fb;
    public $html = '';
    /**
     *
     * @param string $fbTitle item name
     * @param string $fbType
     * @param unknown_type $fbUrl
     * @param string $fbImage (full url to image)
     * @param unknown_type $fbsiteName
     * @param unknown_type $type
     * @return string
     */
    public function faceBookHelper ($fb, $fbTitle = null, $fbType = null, $fbUrl = null, $fbImage = null, $fbsiteName = null, $type = 'page')
    {
    	$settings = Zend_Registry::get('appSettings');
        if($settings->enableFbOpenGraph)
        {

    	$this->view->headMeta()->setProperty('og:title', $fbTitle);
    	$this->view->headMeta()->setProperty('og:type', $fbType);
    	$this->view->headMeta()->setProperty('og:url', $fbUrl);
    	$this->view->headMeta()->setProperty('og:image', $fbImage);
    	$this->view->headMeta()->setProperty('og:site_name', $fbsiteName);
    	$this->view->headMeta()->setProperty('fb:app_id', $fb->getAppId());

    	$this->html = '
        <div id="fb-root"></div>
        <script>
    	(function(d){
	           var js, id = \'facebook-jssdk\', ref = d.getElementsByTagName(\'script\')[0];
	           if (d.getElementById(id)) {return;}
	           js = d.createElement(\'script\'); js.id = id; js.async = true;
	           js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	           ref.parentNode.insertBefore(js, ref);
	         }(document));
        </script>';
    	if($type == 'page')
           $this->html .= '<div class="fb-like" data-layout="button_count" data-width="200" data-show-faces="false"></div>';

	    return $this->html;
        }
    }
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
