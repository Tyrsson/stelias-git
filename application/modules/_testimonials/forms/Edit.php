<?php

class Testimonials_Form_Edit extends Testimonials_Form_Submit
{
	public function init()
	{
		parent::init();
		
		$this->removeElement('captcha');
		$id = new Zend_Form_Element_Hidden('id');
		$status = new Zend_Form_Element_Select('isApproved');
		$status->setLabel('Approve Entry')->setMultiOptions(
				array('0' => 'Unapproved', '1' => 'Approved'));
		$this->addElement($id)
		->addElement($status);
	}
}

?>
