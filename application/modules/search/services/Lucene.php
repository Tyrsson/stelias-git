<?php
class Search_Service_Lucene extends Zend_Search_Lucene
{
    public function addDocument(Zend_Search_Lucene_Document $document) {
        $docRef = $document->docRef;
        //Zend_Debug::dump($docRef);
        $term = new Zend_Search_Lucene_Index_Term($docRef, 'docRef');
        
        $query = new Zend_Search_Lucene_Search_Query_Term($term);
        
        $results = $this->find($query);
        if(count($results) > 0) {
            foreach($results as $result) {
                //Zend_Debug::dump($result->id);
                $this->delete($result->id);
            }
        }
        return parent::addDocument($document);
    }
    public static function create($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Search_Service_Lucene($directory, true));
    }
    public static function open($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Search_Service_Lucene($directory, false));
    }
}