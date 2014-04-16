<?php

/**
 * MediaCore
 *  
 * @author Joey
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Media_Model_MediaCore extends Zend_Db_Table_Abstract
{

    public $settings;
    public function init()
    {
        parent::init();
        $this->settings = Zend_Registry::get('appSettings');
    }
}
