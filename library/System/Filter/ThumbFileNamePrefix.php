<?php
class System_Filter_ThumbFileNamePrefix implements Zend_Filter_Interface
{
    public $prefix = null;

	public function filter($value)
	{
		$appSettings = Zend_Registry::get('appSettings');
		switch( ( isset($appSettings->thumbFileNamePrefix) && !empty($appSettings->thumbFileNamePrefix)) ) {
			case true :
				return $this->prefix !== null ? $this->prefix . $value : $appSettings->thumbFileNamePrefix . $value;
				break;
			default :
				return $value;
				break;
		}
	}
	/**
     * @return the $prefix
     */
    public function getPrefix ()
    {
        return $this->prefix;
    }

	/**
     * @param field_type $prefix
     */
    public function setPrefix ($prefix)
    {
        $this->prefix = $prefix;
    }


}
