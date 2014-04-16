<?php

class Testimonials_Model_Row_Entry extends Aurora_Model_Row_Searchable 
{
    protected $_tableClass = 'Testimonials_Model_Testimonials';
    public function init(){
        parent::init();
    }
    
	/* (non-PHPdoc)
     * @see Aurora_Model_Row_Searchable::getSearchIndexFields()
     */
    public function getSearchIndexFields ()
    {
        $filter = new Zend_Filter_StripTags();
	    $fields['class'] = __CLASS__; 
	    $fields['key'] = $this->_data['id'];
	    $fields['docRef'] = $fields['class'].':'.$fields['key'];
	    $fields['title'] = $this->_data['guestName'];
	    $fields['url'] = '/testimonials/display/'. $fields['key'];
	    $fields['contents'] = $this->_data['content'];
	    $fields['summary'] = substr($filter->filter($this->_data['content']), 0, 300);
	    $fields['createdBy'] = '0';
	    $fields['dateCreated'] = $this->_data['createdDate'];
	    
	    return $fields;
    }
	public function getData()
	{
		return $this->toArray();
	}
}

