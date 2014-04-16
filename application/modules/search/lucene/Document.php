<?php
class Search_Lucene_Document extends Zend_Search_Lucene_Document 
{
  
    public function __construct($fields)
    {
        //Zend_Debug::dump($fields, 'document object');
        $this->addField(Zend_Search_Lucene_Field::keyword('docRef', $fields['docRef']));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('class', $fields['class']));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('key', $fields['key']));
        
        $this->addField(Zend_Search_Lucene_Field::text('title', $fields['title']));
        $this->addField(Zend_Search_Lucene_Field::text('url', $fields['url']));
        
        $this->addField( Zend_Search_Lucene_Field::unStored('contents', $fields['contents']));
        
        $this->addField(Zend_Search_Lucene_Field::unIndexed('summary', $fields['summary']));
        
        $this->addField(Zend_Search_Lucene_Field::keyword('createdBy', $fields['createdBy']));
        $this->addField(Zend_Search_Lucene_Field::keyword('dateCreated', $fields['dateCreated']));
    }

}
