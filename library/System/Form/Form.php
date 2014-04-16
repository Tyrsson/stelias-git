<?php
class System_Form_Form extends Zend_Form
{
	public $appSettings;
	
	public function init()
	{
		parent::init();
		$this->appSettings = new Admin_Model_SettingsGateway();
	}
}