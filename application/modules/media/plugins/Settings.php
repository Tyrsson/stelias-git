<?php
/**
 * This class is a resource class such as Zend_Layout etc.
 * These resourse classes are instantiated by the use of resources.[resourceName] in config files
 * or with the use of bootstrap method $this->bootstrap('settings')
 *
 * This class will then be instantiated and its init method is called.
 *
 * This init method just returns the options (for the resource 'settings')
 * and therfore they end up in the bootstrap storage(Registry)container.
 *
 */
class Media_Plugin_Resource_Settings extends Zend_Application_Resource_ResourceAbstract
{
		public function init(){
			return $this->getOptions();
		}
}