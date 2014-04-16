<?php
class User_Model_Rowset_Users extends Zend_Db_Table_Rowset_Abstract
{
	public function getData()
	{
		try {
			if(isset($this->_data[0]))
			{
				return $this->_data[0];
			} else {
				throw new Dxcore_Db_Exception('$this->_data[0] is undefined');
			}
		} catch (Exception $e) {
			//TODO: Log exception instead of echoing to browser, no need to display since we will recover returning null
			//echo 'Caught Exception:&nbsp;' . $e->getMessage();
			return null;
		}
	}
}