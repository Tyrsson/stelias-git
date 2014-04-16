<?php
class Admin_Model_SettingsGateway
{
	/**
	 *
	 * @var  Admin_Model_AppSettings
	 */
	private $appSettings;
	/**
	 *
	 * @var Admin_Model_ModuleSettings
	 */
	private $moduleSettings;
	public  $bool;

	protected static $_instance = null;
	/**
	 *
	 * @param array $configArray
	 * @return Admin_Settings_Settings
	 */
	public function __construct($configArray = null) {
	    
	    $this->bool = new Zend_Filter_Boolean(array('type' => array(Zend_Filter_Boolean::ALL), 'casting' => false));
	    
		$this->appSettings = new Admin_Model_AppSettings();
		$this->moduleSettings = new Admin_Model_ModuleSettings();
		$settings = $this->appSettings->fetchAll();
		$moduleSettings = $this->moduleSettings->fetchAll();
		foreach($settings as $setting) {
			$this->__set($setting->variable, $setting->value);
		}
		unset($this->appSettings);
		unset($settings);
		unset($setting);
		foreach($moduleSettings as $setting)
		{
			if(isset($setting->moduleName))
			{
				$this->__set($setting->variable, $setting->value);
			}
		}
		unset($this->moduleSettings);
		unset($moduleSettings);
		unset($settings);
		unset($setting);

		if(is_array($configArray))
		{
			foreach($configArray as $k => $v)
			{
				if(is_string($k))
				{
					$this->__set($k, $v);
				}
			}
		}
		unset($configArray);
		unset($k);
		unset($v);
		return $this;
	}
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function toArray() {
    	return (array) $this;
    }
    public static function get($name, $default = null) {

        if($default == null) {
        	return $this->__get($this->$name);
        } else {
            //$this->__set($name, $default);
            if(isset($this->$name)) {
            	return $this->__get($this->$name);
            }
            else {
                $this->__set($name, $default);
                return $this->$name;
            }

        }
    }
	public function __set($name, $value) {
	    //if(is_int($value) && $value <= 2)
		$this->$name = $this->bool->filter($value);
	}
	public function __get($name) {
	    if(isset($this->$name)) {
	        return $this->$name;
	    } else {
	        return null;
	    }
	}
}
