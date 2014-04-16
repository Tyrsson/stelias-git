<?php
class Media_Model_Row_Album extends Aurora_Model_Row_Searchable
{
    protected $_name = 'mediaalbums';
    protected $_primary = 'albumId';
    protected $_tableClass = 'Media_Model_Albums';
    //private   $_mediaPageName;
    
    public function init(){
        parent::init();
        
        
    }
    
    /* (non-PHPdoc)
     * @see Aurora_Model_Row_Searchable::getSearchIndexFields()
    */
    public function getSearchIndexFields ()
    {
        $filter = new Zend_Filter_StripTags();
        $text = isset($this->_data['albumDesc']) ? $this->_data['albumName'] : '';
        $summary = ($text !== '') ? substr($filter->filter($text), 0, 300) : '';
        $time = isset($this->_data['timestamp']) ? $this->_data['timestamp'] : '0';
        $pages = new Pages_Model_Pages();
        $mediaPageName = $pages->fetchPageNameByType();
        
        $fields['class'] = __CLASS__;
        $fields['key'] = $this->_data['albumId'];
        $fields['docRef'] = $fields['class'].':'.$fields['key'];
        $fields['title'] = $this->_data['albumName'];
        $fields['url'] = '/'.$mediaPageName.'?albumName='.$this->_data['albumName'].'&amp;action=album#media-gallery';
        $fields['contents'] = $text;
        $fields['summary'] = $summary;
        $fields['createdBy'] = $this->_data['userId'];
        $fields['dateCreated'] = $time;
        
        return $fields;
    }
    
	public function isChild() {
	    if( ( $this->_data['parentId'] !== 0 )  ) {
	        return true;
	    } elseif ($this->_data['parentId'] == 0) {
	        return false;
	    }
	}
    
}