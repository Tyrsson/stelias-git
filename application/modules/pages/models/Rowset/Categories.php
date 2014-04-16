<?php
require_once ('Zend/Db/Table/Rowset/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * 
     */
class Pages_Model_Rowset_Categories extends Zend_Db_Table_Rowset_Abstract
{
        /**
     * The original data for each row.
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Zend_Db_Table_Abstract object.
     *
     * @var Zend_Db_Table_Abstract
     */
    protected $_table;

    /**
     * Connected is true if we have a reference to a live
     * Zend_Db_Table_Abstract object.
     * This is false after the Rowset has been deserialized.
     *
     * @var boolean
     */
    protected $_connected = true;

    /**
     * Zend_Db_Table_Abstract class name.
     *
     * @var string
     */
    protected $_tableClass = 'Pages_Model_Categories';

    /**
     * Zend_Db_Table_Row_Abstract class name.
     *
     * @var string
     */
    protected $_rowClass = 'Pages_Model_Row_Category'; 

    /**
     * Iterator pointer.
     *
     * @var integer
     */
    protected $_pointer = 0;

    /**
     * How many data rows there are.
     *
     * @var integer
     */
    protected $_count;

    /**
     * Collection of instantiated Zend_Db_Table_Row objects.
     *
     * @var array
     */
    protected $_rows = array();

    /**
     * @var boolean
     */
    protected $_stored = false;

    /**
     * @var boolean
     */
    protected $_readOnly = false;
    
    
}
?>