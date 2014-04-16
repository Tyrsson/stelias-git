<?php
interface System_Db_Table_Form_Interface
{
    const VAR_CHAR = 'varchar';
    const INT      = 'int';
    const TEXT_AREA = 'longtext';
    const DATE     = 'Date'; // for date column matching
    const PARENT_ID  = 'parent'; // used to match parentId columns
    const ENUM = 'enum';
    // Below used to match array indices for needed column(s)
    const DATA_TYPE = 'DATA_TYPE'; //
    const COLUMN_NAME = 'COLUMN_NAME'; // passed to Element constructor -
    const PRIMARY = 'PRIMARY';
    const COLUMN_POSITION = 'COLUMN_POSITION'; // maps to form->setOrder()
    const DEFAULT_VALUE = 'DEFAULT';
    const LENGTH = 'LENGTH';
    const DOJO_ELEMENT_ = 'Zend_Dojo_Form_Element_';
    const ELEMENT_ = 'Zend_Form_Element_'; // to allow calling of standard zend form elements


    public function getFormSchema();
}