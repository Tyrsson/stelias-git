<?php
class Pages_Form_CreatePage extends Zend_Form
{
    //TODO: Populate the parent page name for editing page
    // May have to remove the dojo element and replace it in the child edit form
    // so that we can populate the page name, but that will remove the datastore
    public function init() {

        $types = new Pages_Model_PageTypes();
        $pages = new Pages_Model_Pages();
        $date = new Zend_Date();
        $today = $date->toString('MM/dd/yyyy');

        $this->setMethod('post');

		$name = new Zend_Form_Element_Text('pageName');
		$name->setLabel('Page Name');

		$parent = new Zend_Form_Element_Select('parentId');
		$parent->setLabel('Parent Page');
		$parent->setMultiOptions($pages->fetchParentDropDown());

		$slider = new Zend_Form_Element_Select('showSlider');
		$slider->setLabel('Enable Image Slider');
		$slider->setMultiOptions(array('0' => 'OFF', '1' => 'ON'));

		$showInWidget = new Zend_Form_Element_Checkbox('showInHomeWidget');
		//$showInWidget->setDescription('Only applies to Child Pages');
		$showInWidget->setLabel('Show In Home Page Widget');

        $visibility = new Zend_Form_Element_Select('visibility');
        $visibility->setLabel('Page Visibility');
        $visibility->setOptions(array('value' => 'public'));
        $visibility->setMultiOptions(array('public' => 'public', 'private' => 'private'));

        $pageType = new Zend_Form_Element_Select('pageType');
        $pageType->setLabel('Page Type (Only select this option if this is to be a special page type!)');
        $pageType->setMultiOptions($types->fetchDropDown());

        $roles = new User_Model_Roles();
        $role = new Zend_Form_Element_Select('role');
        $role->setLabel('Min Access Role');
        $role->setMultiOptions($roles->fetchAllRoles());


        $pageText = new Zend_Form_Element_Textarea('pageText');
        $pageText->setAttrib('class', 'editor');
        $pageText->setLabel('Page Content')
        ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->populate(array('createdDate' => $today));

        $this->addElement($name)
        	->addElement($parent)
        	->addElement($slider)
        	->addElement($showInWidget)
        	->addElement($pageType)
        	->addElement($visibility)
        	->addElement($role)
        	->addElement($pageText)
        	->addElement($submit)
        ;
    }
}