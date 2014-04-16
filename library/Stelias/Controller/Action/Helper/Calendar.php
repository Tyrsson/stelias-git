<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Festival Action Helper
 *
 * @uses actionHelper Stelias_Controller_Action_Helper
 */
class Stelias_Controller_Action_Helper_Calendar extends Dxcore_Controller_Action_Helper_Widget
{
    protected $tableName = 'mediafiles'; 
    protected $albumName = 'Calendar'; 
    protected $albumId   = 5; 
    protected $basePath = '/modules/media/images/';
    protected $path = '';
    protected $limit = 1;
    protected $order = 'DESC'; 
    protected $primaryKey = 'fileId'; 
    
    public function __construct()
    {
        parent::__construct();
        $this->path = $this->basePath . $this->albumName;
        $this->table = new Zend_Db_Table($this->tableName);
        $records = $this->table->fetchAll('albumId = 5', "$this->primaryKey  $this->order", "$this->limit", 0);
        $this->data->path = $this->path;
        $this->data->records = $records;
    }
    public function buildWidget ()
    {
        $this->renderWidget($this->data, 'pages');
    }

}
