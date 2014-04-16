<?php
class System_Controller_Router_Route extends Zend_Controller_Router_Route
{
	public $name;
	/* (non-PHPdoc)
	 * @see Zend_Controller_Router_Route::__construct()
	 */
	public function __construct($route, array $defaults = array(), array $reqs = array(), Zend_Translate $translator = null, $locale = null) {
		$route               = trim($route, $this->_urlDelimiter);
		$this->_defaults     = (array) $defaults;
		$this->_requirements = (array) $reqs;
		$this->_translator   = $translator;
		$this->_locale       = $locale;

		if ($route !== '') {
			foreach (explode($this->_urlDelimiter, $route) as $pos => $part) {
				if (substr($part, 0, 1) == $this->_urlVariable && substr($part, 1, 1) != $this->_urlVariable) {
					$name = substr($part, 1);


					if (substr($name, 0, 1) === '@' && substr($name, 1, 1) !== '@') {
						$name                  = substr($name, 1);

						$this->_translatable[] = $name;
						$this->_isTranslated   = true;
					}

					$this->_parts[$pos]     = (isset($reqs[$name]) ? $reqs[$name] : $this->_defaultRegex);
					$this->_variables[$pos] = $name;
					$this->setName($name);
				} else {
					if (substr($part, 0, 1) == $this->_urlVariable) {
						$part = substr($part, 1);
					}

					if (substr($part, 0, 1) === '@' && substr($part, 1, 1) !== '@') {
						$this->_isTranslated = true;
					}

					$this->_parts[$pos] = $part;

					if ($part !== '*') {
						$this->_staticCount++;
					}
				}
			}
		}
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function getName()
	{
		return $this->name;
	}
}