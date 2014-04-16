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
class System_View_Helper_SeoHelper extends Zend_View_Helper_Abstract
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
    public $keywords;
    public function seoHelper ($itemKeyWords = null)
    {
    	if($itemKeyWords !== null) {
    		$this->itemKeyWords = $itemKeyWords;
    	}
        $this->appSettings = Zend_Registry::get('appSettings');
    	$this->view->headMeta()->setName('keywords', $this->getKeyWords());
    	$this->view->headMeta()->setName('description', $this->getDescription());
    }
    public function getKeyWords()
    {
    	if(isset($this->appSettings->seoKeyWords) && is_string($this->appSettings->seoKeyWords) && $this->appSettings->seoKeyWords !== "") {

    		//$appKeyWords = explode(',', $this->appSettings->seoKeyWords);
    		//$keywords = implode(',', array_reverse( array_merge( $appKeyWords, $this->itemKeyWords ) ) );
    		if(isset($this->itemKeyWords)) {
    			$this->keywords = $this->itemKeyWords . ', ' . $this->appSettings->seoKeyWords;
    		} else {
    			$this->keywords = $this->appSettings->seoKeyWords;
    		}
    	    return $this->keywords;
    	} else {
    	    return "";
    	}
    }
    public function getDescription()
    {
    	if(isset($this->appSettings->seoDescription) && is_string($this->appSettings->seoDescription) && $this->appSettings->seoDescription !== "") {
    	    return $this->appSettings->seoDescription;
    	} else {
    	    return "";
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
