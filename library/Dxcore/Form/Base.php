<?php
class Dxcore_Form_Base extends Zend_Form
{
	public function init() {
		
		$hash = new Zend_Form_Element_Hash('hash', 'xss-hash', array('salt' => 'unique'));
				
		$this->addElement($hash);
		
	}
}