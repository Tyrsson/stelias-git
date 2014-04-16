<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * CmdHanlder Action Helper
 *
 * @uses actionHelper Dxcore_Controller_Action_Helper
 */
class Search_Controller_Action_Helper_SearchCmdHandler extends Zend_Controller_Action_Helper_Abstract
{

    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    public $actions = array();
    public $module;
    public $action;
    public $controller;
    public $indexPath;
    public $log;
    public $documentClass;
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct ()
    {
       
    }
    
    public function preDispatch ()
    {
        
        $this->request = $this->getRequest();
        $this->controller = $this->getActionController();
        $this->module = $this->request->getModuleName();
        $this->action = $this->request->getActionName();
        $this->documentClass = 'Search_Lucene_Document_'.ucfirst($this->module);
        // path to the current modules search index
        //$this->path = $this->controller->searchIndexPath;
        $this->indexPath = $this->controller->searchIndexPath;
        
        $this->log = $this->controller->log;
        switch($this->module) {
            case 'pages' :
            case 'media' :
            case 'testimonials' :
                break;
            default:
                //return;
                break;
        }
    }
    


    public function build(Zend_Db_Table_Abstract $table, $module) {
        
        
        $documentClass = 'Search_Lucene_Document_'.ucfirst($module);
        $path = $this->indexPath .= DIRECTORY_SEPARATOR . $module;
        $index = Zend_Search_Lucene::create($path);
        if($table !== null) {
            foreach($table->fetchAll() as $row) {
                $index->addDocument(new $documentClass($row));
                continue;
            }
        } else {
            $this->log->warn($this->module . ' search index could not be populated as $table was null');
        }
        $this->log->info($this->module. ' search index was built @ '. Zend_Date::now());
        
        $index->commit();
        unset($index);
    }

	/**
     * direct(): Perform helper when called as
     * $this->_helper->SearchCmdHandler($term, $recordId, $document, $table)
     *
     * @param  string $term
     * @param  mixed string|int $recordId
     * @param  object Zend_Db_Table_Row_Abstract $document
     * @param  object Zend_Db_Table_Abstract $params
     * @return void
     */
    public function direct (Zend_Db_Table_Abstract $table, $module)
    {
        $this->build($table, $module);
    }
}
