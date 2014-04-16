<?php
/**
 * @author Joey Smith
 * @version 0.9.1
 * @package Dxcore
 */
require_once ('Zend/Application/Module/Bootstrap.php');
class Calendar_Bootstrap extends Dxcore_Application_Module_Bootstrap
{
        /*
     * @var boolean flag to include front end navigation to be overridden in class childern
     */
    protected $hasFrontEndNav = false;
    /*
     * @var boolean flag to include admin navigation to be overridden in class childern
     */
    protected $hasAdminNav = true;

}