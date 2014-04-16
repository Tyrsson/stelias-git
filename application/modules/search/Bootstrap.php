<?php
/**
 * @author Joey Smith
 * @version 0.9
 * @package Aurora
 */
require_once ('Zend/Application/Module/Bootstrap.php');
class Search_Bootstrap extends Dxcore_Application_Module_Bootstrap
{
    protected $hasFrontEndNav = false;
    protected $hasAdminNav = true;
    protected function _initModuleAutoloader() {
        
        $this->_resourceLoader->addResourceTypes(array(
                'lucene' => array(
                        'path' => 'lucene/',
                        'namespace' => 'Lucene'
                ),
                'documents' => array(
                        'path' => 'lucene/documents',
                        'namespace' => 'Lucene_Document'
                ),
                'data' => array(
                        'path' => 'lucene/data',
                        'namespace' => 'Lucene_Data'
                ),
                'controllerplugins' => array(
                        'path' => 'controllers/plugins',
                        'namespace' => 'Controller_Plugin'
                ),
                'actionhelpers' => array(
                        'path' => 'controllers/action/helpers',
                        'namespace' => 'Controller_Action_Helper'
                        )
        ));
        parent::_initModuleAutoloader();
    }
   /* protected function _initSearchIndexService() {
    	$serviceIndexer = new Search_Service_SearchIndexer();
        $serviceIndexer->setIndexDirectory(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'search');
        $subject = Aurora_Model_Row_Observerable::getisntance();
        $subject->attach($serviceIndexer);
        Zend_Debug::dump($subject);
    }*/
    
}