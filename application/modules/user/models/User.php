<?php
/**
 * User Model
 *
 * @author Joey Smith
 * @version 0.1
 */
require_once 'Zend/Db/Table/Abstract.php';
class User_Model_User extends User_Model_DbTable_User
{
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
	protected $_referenceMap = array('pageOwner' => array(
														'columns' => array('userId'),
														'refTableClass' => 'Pages_Model_PageLookup',
														'refColumns' => array('userId'),
														'onDelete'   => 'cascade',
														'onUpdate'   => 'cascade'
													)
								);


    public function getOnePage($page = 1) {
        $query = $this->select();
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setItemCountPerPage(8);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
}