<?php
class Admin_Form_AppSettings extends Dxcore_Form_Base
{

	public function init()
	{
		parent::init();

		$siteName = new Zend_Form_Element_Text('siteName');
		$siteName->setLabel('Web Masters Email:')
		//->setOptions()
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('HtmlEntities')
		->addFilter('StringTrim');

		$webMasterEmail = new Zend_Form_Element_Text('webMasterEmail');
		$webMasterEmail->setLabel('Web Masters Email:')
		//->setOptions()
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('HtmlEntities')
		->addFilter('StringTrim');

		$reCaptcha = new Zend_Form_Element_Checkbox('enableCaptcha');
		$reCaptcha->setLabel('Enable ReCaptcha ?')
		->setRequired(true);

		$reCaptchaPrivateKey = new Zend_Form_Element_Text('recaptchaPrivateKey');
		$reCaptchaPrivateKey->setLabel('ReCaptcha Private Key:')
		->addFilter('StringTrim');

		$reCaptchaPublicKey = new Zend_Form_Element_Text('recaptchaPublicKey');
		$reCaptchaPublicKey->setLabel('ReCaptcha Private Key:')
		->addFilter('StringTrim');

		$seoKeyWords = new Zend_Form_Element_Text('seoKeyWords');
		$seoKeyWords->setLabel('Seo Key Words:')
		//->setOptions(array('size' => '35'))
		->setRequired(true)
		->addValidator('NotEmpty', true)
		//->addFilter('StringTrim')
		;

		$seoDescription = new Zend_Form_Element_Textarea('seoDescription');
		$seoDescription->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$allowTags = new Zend_Form_Element_Textarea('allowTags');
		$allowTags->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit')
		->setOptions(array('class' => 'submit'));

		$this->addElement($siteName)
		->addElement($webMasterEmail)
		->addElement($reCaptcha)
		->addElement($reCaptchaPrivateKey)
		->addElement($reCaptchaPublicKey)
		->addElement($seoKeyWords)
		->addElement($seoDescription)
		->addElement($allowTags)
		->addElement($submit);
	}
}