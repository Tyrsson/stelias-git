<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * RecentMedia helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_RecentMedia
{
    public $albums;
    public $files;
    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function recentMedia ()
    {

        $this->albums = new Media_Model_Albums();
        $this->files = new Media_Model_Files();



        //return 'running from System Library';
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
