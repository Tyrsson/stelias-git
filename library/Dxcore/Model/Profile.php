<?php
/**
 * Profile
 * 
 * @author jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Dxcore_Model_Profile extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'profile';
    protected $_primary = 'userId';
    protected $_referenceMap    = array(
        'Owner' => array(
            'columns'           => 'created_by',
            'refTableClass'     => 'Dxcore_Model_Users',
            'refColumns'        => 'userId'
        ),
    );
}
