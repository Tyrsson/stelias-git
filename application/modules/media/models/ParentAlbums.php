<?php
/**
 * This file is not being used at the moment but most likely will be in the next minor version 
 * @author Joey
 *
 */
class Media_Model_ParentAlbums
{
    public $parentId;
    public $parentName;
    public $tableClass;
    public $hasParent = false;
    public $hasGrandParent = false;
    public $parentRow;
    public $parents = array();
    
    
    public function __construct(Media_Model_Row_Album $current)
    {
        
        try {
            
            if(!$current instanceof Media_Model_Row_Album) 
            {
                throw new Zend_Db_Table_Exception('Argument 1 must be instance of Media_Model_Row_Album' . __FILE__ . '::' . __LINE__);
            }
            if(!isset($current->albumId)) 
            {
                throw new Zend_Db_Table_Row_Exception('Column albumId was not set in the row' . __FILE__ . '::' . __LINE__);
            }
            //Media_Model_Albums() instance
             $this->setTableClass($current->getTable());
             $this->setParentId($current->parentId);
             $this->setParentRow($this->tableClass->fetchById($this->parentId));
             
             if($this->getParentRow() !== null) 
             {
                 $this->setHasParent(true);
                 $this->parents[] = $this->getParentRow();
             }
             if($this->getHasParent()) 
             {
                 if(isset($this->parentRow->parentId) && $this->parentRow->parentId !== 0) 
                 {
                     $this->setHasGrandParent(true);
                 }
                 if($this->getHasGrandParent())
                 {
                     $grandparent = new self($this->parentRow);
                 }
             }
             return $this;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
	/**
     * @return the $hasParent
     */
    public function getHasParent ()
    {
        return $this->hasParent;
    }

	/**
     * @return the $hasGrandParent
     */
    public function getHasGrandParent ()
    {
        return $this->hasGrandParent;
    }

	/**
     * @return the $parentRow
     */
    public function getParentRow ()
    {
        return $this->parentRow;
    }

	/**
     * @param boolean $hasParent
     */
    public function setHasParent ($hasParent)
    {
        $this->hasParent = $hasParent;
    }

	/**
     * @param boolean $hasGrandParent
     */
    public function setHasGrandParent ($hasGrandParent)
    {
        $this->hasGrandParent = $hasGrandParent;
    }

	/**
     * @param field_type $parentRow
     */
    public function setParentRow ($parentRow)
    {
        $this->parentRow = $parentRow;
    }

	/**
     * @return the $parentId
     */
    public function getParentId ()
    {
        return $this->parentId;
    }

	/**
     * @return the $parentName
     */
    public function getParentName ()
    {
        return $this->parentName;
    }

	/**
     * @return the $tableClass
     */
    public function getTableClass ()
    {
        return $this->tableClass;
    }

	/**
     * @param field_type $parentId
     */
    public function setParentId ($parentId)
    {
        $this->parentId = $parentId;
    }

	/**
     * @param field_type $parentName
     */
    public function setParentName ($parentName)
    {
        $this->parentName = $parentName;
    }

	/**
     * @param Ambigous <Zend_Db_Table_Abstract, NULL> $tableClass
     */
    public function setTableClass ($tableClass)
    {
        $this->tableClass = $tableClass;
    }

    
}