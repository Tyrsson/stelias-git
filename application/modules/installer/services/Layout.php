<?php
class Installer_Service_Layout extends Installer_Service_InstallerAbstract
{
    
    public function init()
    {
        // have to have this if the skin comes with a layout file
        switch(is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts')) {
            case true :
                $this->layoutPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts';
                if(!is_writable($this->layoutPath)) {
                    throw new Zend_Application_Exception('Layout directory is not writable.');
                }
                break;
        
            case false :
                throw new Zend_Application_Exception('Layout directory not found.');
                break;
        }
    }
    
}