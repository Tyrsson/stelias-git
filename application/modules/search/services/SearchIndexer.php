<?php
class Search_Service_SearchIndexer
{
	public $indexDirectory = null;
	
	public function __construct($path = null){
		if($path !== null) {
			$this->setIndexDirectory($path);
		}
	}
    public function setIndexDirectory($path = null)
    {
    	if($path === null) {
    		$this->indexDirectory = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'search';
    	} else {
    		$this->indexDirectory = $path;
    	}
    }

    public function getIndexDirectory()
    {
        if($this->indexDirectory === null)
        {
            $this->setIndexDirectory();
        }
        return $this->indexDirectory;
    }
    /*public static function getDocument($row) {
         if(method_exists($row, 'getSearchIndexFields')) {
             $fields = $row->getSearchIndexFields($row);
             $doc = new Search_Lucene_Document($fields);
             return $doc;
         }
         return false;
    }*/

    public function _addToIndex($event) 
    {
         $data = $event->getParams();
         //Zend_Debug::dump($data, '_addToIndex');
         //die('ran to here');
         //die('ran to _addToIndex');
         //$dir = $this->indexDirectory;
         $index = Search_Service_Lucene::open($this->getIndexDirectory());
         //Zend_Debug::dump($index, '_addToIndex - $index');
         //die('_addToIndex');
         $index->addDocument(new Search_Lucene_Document($data));
         $index->commit();
    }
    public function _optimizeIndex()
    {
        $index = Search_Service_Lucene::open($this->getIndexDirectory());
        $index->optimize();
    }
}