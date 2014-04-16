<?php
abstract class System_Model_Row_Searchable extends Zend_Db_Table_Row_Abstract
{
    protected $searchIndexer;
    protected $events;
    protected $baseUrl;
//     protected $fields = array('class','key', 'docRef', 'title', 'contents', 'summary', 'createdBy', 'dateCreated');
    public function init(){
        $this->events();
    }
    public function events() {
        if($this->events === null) {
            require_once(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'search' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'SearchIndexer.php');
            $indexer = new Search_Service_SearchIndexer();
            $this->events = new Zend_EventManager_EventManager();
            $this->events->attach(array('_postInsert', '_postUpdate', '_postDelete'), array($indexer, '_addToIndex'), null);

        }
        return $this->events;
    }
    abstract public function getSearchIndexFields();

    protected function _postInsert()
    {
       $this->events()->trigger('_postInsert', get_called_class(), $this->getSearchIndexFields());
    }
    protected function _postUpdate() 
    {
        $this->events()->trigger('_postUpdate', get_called_class(), $this->getSearchIndexFields());
    }
    protected function _postDelete()
    {
        $this->events()->trigger('_postDelete', get_called_class(), $this->getSearchIndexFields());
    }

}