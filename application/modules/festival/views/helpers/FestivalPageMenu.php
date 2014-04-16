<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * FestivalPageMenu helper
 *
 * @uses viewHelper Stelias_View_Helper
 */
class Festival_View_Helper_FestivalPageMenu
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public $html;
    protected $pages;
    protected $pageTypes;
    protected $types = array('festival', 'ordering', 'menu');
    protected $date;
    protected $appSettings;
    public function festivalPageMenu ()
    {
        $this->appSettings = Zend_Registry::get('appSettings');

        $this->pages = new Pages_Model_Pages();
        $this->date = new Zend_Date();
        $pageList = $this->pages->fetchByType($this->types, $nameOnly = false, $childrenOnly = true, $order = 'ASC');

        $this->html = '<div class="grid_12 buttonset" style="margin-top:20px;">';

        foreach($pageList as $page) {

            $this->html .= '<a href="/'.$page->pageUrl.'">'.$page->pageName.'</a>';

        }

            $this->html .= '
                      </div>
                      <div class="clear"></div>';
        return $this->html;
    }

    /**
     * Sets the view field
     *
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
