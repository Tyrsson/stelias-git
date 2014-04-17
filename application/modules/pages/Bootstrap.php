<?php
require_once ('Zend/Application/Module/Bootstrap.php');
class Pages_Bootstrap extends Dxcore_Application_Module_Bootstrap
{
    /*
     * @var boolean flag to include front end navigation to be overridden in class childern
    */
    protected $hasFrontEndNav = false;
    /*
     * @var boolean flag to include admin navigation to be overridden in class childern
    */
    protected $hasAdminNav = true;
    protected function _initHomeActionHelper() {
        //die('running...');
        Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_Home());
    }
    protected function _initContactActionHelper() {
        Zend_Controller_Action_HelperBroker::addHelper(new Dxcore_Controller_Action_Helper_Contact());
    }
    protected function _initFestivalActionHelper() {
        Zend_Controller_Action_HelperBroker::addHelper(new Stelias_Controller_Action_Helper_Festival());
    }
//     protected function _initOrderingActionHelper() {
//         Zend_Controller_Action_HelperBroker::addHelper(new Stelias_Controller_Action_Helper_Ordering());
//     }
    protected function _initBulletingActionHelper() {
        Zend_Controller_Action_HelperBroker::addHelper(new Stelias_Controller_Action_Helper_Bulletin());
    }
    protected function _initCalendarActionHelper() {
        Zend_Controller_Action_HelperBroker::addHelper(new Stelias_Controller_Action_Helper_Calendar());
    }
}