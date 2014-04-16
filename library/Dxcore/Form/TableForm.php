<?php
abstract class Dxcore_Form_TableForm 
{
	/**
	 * 
	 * @var (object) $tableClass
	 * @see Zend_Db_Table_Abstract
	 */
	public $tableClass;
	/**
	 * 
	 * @var (array) $columns
	 * $columnName => formElements_Type (text, checkbox, etc)
	 */
	public $columns;
	/**
	 * 
	 * @var (array) $options
	 */
	public $options;
	/**
	 * 
	 * @var (object) $form
	 * @see Zend_Dojo_Form
	 */
	/**
	 * 
	 * @var (array) $elements
	 */
	public $elements;
	public $form;
	
	/**
	 * 
	 * @param  $tableClass
	 * @param mixed $columns
	 */
	public function __construct($tableName = null, $tableClass, $columns) {
		// must be class name as string or a table class object
		if (is_string($tableClass)) {
			$table = $tableClass;
			$object = new $table();
			$this->setTableClass($object);
		} 
		elseif($tableClass instanceof Zend_Db_Table_Abstract || is_subclass_of($tableClass, Zend_Db_Table_Abstract) ) {
			$this->setTableClass($tableClass);
		}
		//TODO: Add registry key for meta cache and pass it above when the tableClass object is created
		//$tableMetaData = $this->tableClass->setMetadataCacheInClass(true);
		
		if (is_string($columns)) {
			// normalize this so we know we are checking the correct case
			$columns = strtolower($columns);
			if($columns === 'all') {
				// here we want to populate all columns as a form field
				$dbColumns = $this->tableClass->getMetadataCache();
			}
		}
		elseif(is_array($columns)) {
			// here we only want to populate a select group of columns as form fields
			foreach($columns as $column => $field) {
				
			}
		}
	}
	/**
	 * 
	 */
	public function init() {
		
	}
	public function getTable($className) {
		
	}
	/**
	 * @return the $tableClass
	 */
	public function getTableClass() {
		return $this->tableClass;
	}

	/**
	 * @return the $columns
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @return the $form
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * @param (string) $tableClass
	 */
	public function setTableClass($tableClass) {
		$this->tableClass = $tableClass;
	}

	/**
	 * @param (array) $columns
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
	}

	/**
	 * @param (array) $options
	 */
	public function setOptions($options) {
		$this->options = $options;
	}
	
	/**
	 * @param unknown_type $form
	 */
	public function setForm($form) {
		$this->form = $form;
	}

}