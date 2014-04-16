<?php
require_once ('Zend/Db/Table/Row/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * 
     */
class Pages_Model_Row_Category extends Zend_Db_Table_Row_Abstract
{
    /**
     * The data for each column in the row (column_name => value).
     * The keys must match the physical names of columns in the
     * table for which this row is defined.
     *
     * @var array
     */
    protected $_data = array();
    /**
     * This is set to a copy of $_data when the data is fetched from
     * a database, specified as a new tuple in the constructor, or
     * when dirty data is posted to the database with save().
     *
     * @var array
     */
    protected $_cleanData = array();
    /**
     * Tracks columns where data has been updated. Allows more specific insert and
     * update operations.
     *
     * @var array
     */
    protected $_modifiedFields = array();
    /**
     * Connected is true if we have a reference to a live
     * Zend_Db_Table_Abstract object.
     * This is false after the Rowset has been deserialized.
     *
     * @var boolean
     */
    protected $_connected = true;
    /**
     * A row is marked read only if it contains columns that are not physically represented within
     * the database schema (e.g. evaluated columns/Zend_Db_Expr columns). This can also be passed
     * as a run-time config options as a means of protecting row data.
     *
     * @var boolean
     */
    protected $_readOnly = false;
    /**
     * Name of the class of the Zend_Db_Table_Abstract object.
     *
     * @var string
     */
    protected $_tableClass = 'Pages_Model_Categories';
    /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $_primary = 'categoryId';
    
    public    $dirPath;
    
    public    $basePath;
    
    public    $categoryNameAsId = true;
    
    public function init() {
        self::setBasePath();
    }
    public function setBasePath() {
        $this->basePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'categories';
    }
    public function getBasePath() {
        return $this->basePath;
    }
    public function setAlbumPath($categoryName) {
    
        $path = self::getBasePath();
    
        try {
            if(is_dir($path) && is_writable($path)) {
                $this->dirPath = $path . DIRECTORY_SEPARATOR . $categoryName;
            } else {
                throw new Zend_Application_Exception('The folder /modules/pages/categories is not writable, please contact your server admin.');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    
    }
    
    public function createAlbum() {
        
        if($this->categoryNameAsId === true) {
            self::setAlbumPath($this->_data['categoryId']);
        } 
        
        if($this->categoryNameAsId === false) {
            self::setAlbumPath($this->_data['categoryName']);
        }
        $this->localPath = $this->dirPath;
        $this->save();
        // If not (returns false) throw error
        if(!mkdir( $this->dirPath ))
        {
            return false;
        }
        else {
            // Directory was created so lets return true
            return true;
        }
    }
    public function getAlbumPath() {
        return $this->dirPath;
    }
}