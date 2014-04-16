<?php

/**
 * @author Joey
 *
 */
interface System_Model_DbTable_SystemInterface {
	public function fetchDropDown($valueColumn, $withDefault = false, $defaultId = 0, $defaultLabel = 'None', $asArray = true);
	public function getPrimaryKey();
}
