<?php
class System_Dojo_Form extends System_Dojo_Form
{
    public $tabContainer;
    
    public function init() 
    {
        $this->setMethod('post');
	    
	    $this->tabContainer = new System_Dojo_Form_SubForm();
	    
	    //Start Main Form
	    $this->setAttribs(array(
	            'name'  => 'wSFO',
	            'jsId'  => 'wSFO'
	    ));
	    
	    $this->tabContainer->setDecorators(array(
	            'FormElements',
	            array('TabContainer', array(
	                    'id' => 'tabContainer',
	                    'style' => 'width: 800px; height: 300px;',
	                    'dijitParams' => array(
	                            'tabPosition' => 'top'
	                    ),
	            )),
	            'DijitForm',
	    ));
	    
	    $this->addSubForm($this->tabContainer, 'tabs');
	    
    }
    public function addSubmit($label = null) {
        $submitButton = new System_Dojo_Form_Element_SubmitButton('savewSFO');
        if(null !== $label) {
            $submitButton->setLabel($label);
        }
        $elementCount = $this->count();
        $submitButton->setOrder($elementCount++);
        $this->addElement($submitButton);
    }
}