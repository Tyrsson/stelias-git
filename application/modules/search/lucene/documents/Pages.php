<?php
class Search_Lucene_Document_Pages extends Zend_Search_Lucene_Document
{
    public $user;
    public $filter;
    public $log;
    public $docData = null;
    
    public function __construct($document)
    {
        $filter = new Zend_Filter_StripTags();
    
        //$this->log = $this->getLog();
        //$this->user = new User_Model_User();
    
        //$this->user = new User_Model_User();
    
        $this->addField(Zend_Search_Lucene_Field::keyword('page_id', $document->pageId));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('page_url', $document->pageUrl));
        //$this->addField(Zend_Search_Lucene_Field::unIndexed('pageUrl', $page->pageUrl));
        $this->addField(Zend_Search_Lucene_Field::unIndexed('date', $document->createdDate));
        $this->addField(Zend_Search_Lucene_Field::text('title', $document->pageName));
        //substr($widget->pageText, 0, 300)
        $this->addField( Zend_Search_Lucene_Field::text('teaser', substr($filter->filter($document->pageName), 0, 300) ) ) ;
        //$this->addField(Zend_Search_Lucene_Field::text('author', $data->firstName . ' ' . $data->lastName));
        $this->addField(Zend_Search_Lucene_Field::unStored('content', $document->pageText));
    }
    public function getLog()
    {
        return Zend_Registry::get('log');
    }
}