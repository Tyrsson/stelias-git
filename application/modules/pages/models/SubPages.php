<?php
/**
 * SubPages
 *  
 * @author Joey
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Pages_Model_SubPages 
{
    public $parentId;
    
    public $page;
    
    public $subPages;
    
    public function __construct(object $page) {
        if (!($page instanceof Pages_Model_Row_Page)) {
        	throw new Dxcore_Db_Exception('$page must be an instance of Pages_Model_Pages');
        }
        $this->setPage($page);
        
    }
	/**
     * @return the $parentId
     */
    public function getParentId ()
    {
        return $this->parentId;
    }

	/**
     * @param field_type $parentId
     */
    public function setParentId ($parentId)
    {
        $this->parentId = $parentId;
    }

	/**
     * @return the $page
     */
    public function getPage ()
    {
        return $this->page;
    }

	/**
     * @param Pages_Model_Pages $page
     */
    public function setPage ($page)
    {
        $this->page = $page;
    }

	/**
     * @return the $subPages
     */
    public function getSubPages ()
    {
        return $this->subPages;
    }

	/**
     * @param field_type $subPages
     */
    public function setSubPages ($subPages)
    {
        $this->subPages = $subPages;
    }

    
}
