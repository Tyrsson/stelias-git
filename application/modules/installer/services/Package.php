<?php
class Installer_Service_Package
{
    protected static $_instance = null;
    
    private function __construct() {}
    private function __clone() {}
    
    public $packageType = 'skin'; // this is all we can install as a package at the moment
    public $installer = null;
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function setpackageType($type)
    {
        switch($type) {
            case 'skin' :
                
                break;
                
            case 'module' :
            default:
                throw new Zend_Application_Exception('Only Skin installation is supported at this time.');
                break;
        }
    }
    public function setpackageArchive($archive)
    {
        
    }
	/**
     * @return the $installer
     */
    public function getInstaller ()
    {
        return $this->installer;
    }

	/**
     * @param NULL $installer
     */
    public function setInstaller ($installer)
    {
        $this->installer = $installer;
    }

    
}