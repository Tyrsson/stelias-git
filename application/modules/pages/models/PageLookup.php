<?php
require_once ('Zend/Db/Table/Abstract.php');
    /**
     * @author Joey Smith
     * @version 0.9.1
     * @package Pages
     */
class Pages_Model_PageLookup extends Zend_Db_Table_Abstract
{
    protected $_name = 'pageLookup';
    protected $_primary = 'pageId';
    protected $sequence = false;

    protected $_dependentTable = array('Pages_Model_Pages');
    /**
     * Associative array map of declarative referential integrity rules.
     * This array has one entry per foreign key in the current table.
     * Each key is a mnemonic name for one reference rule.
     *
     * Each value is also an associative array, with the following keys:
     * - columns       = array of names of column(s) in the child table.
     * - refTableClass = class name of the parent table.
     * - refColumns    = array of names of column(s) in the parent table,
     *                   in the same order as those in the 'columns' entry.
     * - onDelete      = "cascade" means that a delete in the parent table also
     *                   causes a delete of referencing rows in the child table.
     * - onUpdate      = "cascade" means that an update of primary key values in
     *                   the parent table also causes an update of referencing
     *                   rows in the child table.
     *
     * @var array
     */
//     protected $_referenceMap = array(
//                             'Owner' => array(
//                     			'columns' => 'userId',
//                     			'refTableClass' => 'User_Model_Users',
//                     			'refColumns' => 'userId',
//                     			'onDelete'   => 'cascade',
//                     			'onUpdate'   => 'cascade'
//                        		),
//                             'Categories' => array(
//                     			'columns' => 'categoryId',
//                     			'refTableClass' => 'Pages_Model_Categories',
//                     			'refColumns' => 'categoryId',
//                     			'onDelete'   => 'cascade',
//                     			'onUpdate'   => 'cascade'
//                        		),
//                     		'Files' => array(
//                     			'columns' => 'fileId',
//                     			'refTableClass' => 'Pages_Model_Files',
//                     			'refColumns' => 'fileId',
//                     			'onDelete'   => 'cascade',
//                     			'onUpdate'   => 'cascade'
//                        		),
//                        		'Comments' => array(
//                        			'columns' => 'pageId',
//                        			'refTableClass' => 'Pages_Model_Comments',
//                        			'refColumns' => 'pageId',
//                        			'onDelete'   => 'cascade',
//                        			'onUpdate'   => 'cascade'
//                        		)
//                     	);
}