<?php
require_once ('Zend/Db/Table/Row/Abstract.php');
    /** 
     * @author Joey Smith
     * @version 0.9.1
     * @package Pages
     */
class Pages_Model_Row_File extends Zend_Db_Table_Row_Abstract
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
     * Zend_Db_Table_Abstract parent class or instance.
     *
     * @var Zend_Db_Table_Abstract
     */
    //protected $_table;

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
    protected $_tableClass = 'Pages_Model_Files';

    /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $_primary = 'fileId';
    private $tags = null;
	private $user = null;

	/**
	 * Allows pre-insert logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _insert()
	{	
	}
	
	/**
	 * Allows post-insert logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postInsert()
	{
		$lookup = new Pages_Model_PageLookup();
		$row = $lookup->fetchNew();
		$row->setFromArray($this->_data);
		$row->save();		
	}
	
	/**
	 * Allows pre-update logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _update()
	{
	}
	
	/**
	 * Allows post-update logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postUpdate()
	{
	}
	
	/**
	 * Allows pre-delete logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _delete()
	{
	}
	
	/**
	 * Allows post-delete logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postDelete()
	{
	}
}