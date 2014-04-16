<?php


/**
 * @author Joey
 * This class is not yet implemented
 */
class System_Form_Element_File extends Zend_Form_Element_File {

	public $newFileName = '';
	public function setOptions(array $options) {

		if(isset($options['rename'])) {
			$this->setNewFileName($options['rename']);
			unset($options['rename']);
			$this->rename();
		}
		parent::setOptions($options);

	}
	public function rename() {
		// code to call rename filter using set class property
	}
	/**
	 * @return the $newFileName
	 */
	public function getNewFileName() {
		return $this->newFileName;
	}

	/**
	 * @param string $newFileName
	 */
	public function setNewFileName($newFileName) {
		$this->newFileName = $newFileName;
	}


}
