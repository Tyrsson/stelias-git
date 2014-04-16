<?php
require_once ('Zend/Db/Table/Row/Abstract.php');
/** 
 * @author Joey Smith
 * 
 * 
 */

class User_Model_Row_User extends Zend_Db_Table_Row_Abstract
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
	protected $_tableClass = 'User_Model_Users';
	
	
	public function getUser()
	{
		if(isset($this->_data))
		{
			return $this->_data;
		}
	}
	public function getCompanyName()
	{
		
	}
	public function getRole()
	{
		if(isset($this->_data))
		{
			return (string) $this->_data['role'];
		}
	}
	public function getData()
	{
		return $this->_data;
	}	
}