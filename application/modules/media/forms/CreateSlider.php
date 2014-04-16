<?php
class Media_Form_CreateSlider extends Zend_Form
{
	public function init()
	{
		
		$options = array(0 => 'OFF', 1 => 'ON');
		
		$sliderSettings = new Media_Model_SliderSettings();
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Slider Name');
		$name->setRequired(true);
		
		$isActive = new Zend_Form_Element_Select('isActive');
		$isActive->setLabel('Activate Slider');
		$isActive->setMultiOptions($options);
		
		$effect = new Zend_Form_Element_Select('effect');
		$effect->setLabel('Slider Effect');
		$effect->setMultiOptions($sliderSettings->effects);
		$effect->setRequired(true);
		
		$slices = new Zend_Form_Element_Text('slices');
		$slices->setLabel('Number of Slices');
		
		$boxCols = new Zend_Form_Element_Text('boxCols');
		$boxCols->setLabel('Box Columns');
		
		$boxRows = new Zend_Form_Element_Text('boxRows');
		$boxRows->setLabel('Box Rows');
		
		$animSpeed = new Zend_Form_Element_Text('animSpeed');
		$animSpeed->setLabel('Animation Speed');
		
		$pauseTime = new Zend_Form_Element_Text('pauseTime');
		$pauseTime->setLabel('Pause Time');
		
		$directionNav = new Zend_Form_Element_Select('directionNav');
		$directionNav->setLabel('Directional Navigation');
		$directionNav->setMultiOptions($options);
		
		$controlNav = new Zend_Form_Element_Select('controlNav');
		$controlNav->setLabel('Control Navigation');
		$controlNav->setMultiOptions($options);
		
		$controlNavThumbs = new Zend_Form_Element_Select('controlNavThumbs');
		$controlNavThumbs->setLabel('Control Navigation Thumbnails');
		$controlNavThumbs->setMultiOptions($options);
		
		$pauseOnHover = new Zend_Form_Element_Select('pauseOnHover'); 
		$pauseOnHover->setLabel('Pause On Hover');
		$pauseOnHover->setMultiOptions($options);
		
		$manualAdvance = new Zend_Form_Element_Select('manualAdvance'); 
		$manualAdvance->setLabel('Manual Advance');
		$manualAdvance->setMultiOptions($options);
		
		$prevText = new Zend_Form_Element_Text('prevText');
		$prevText->setLabel('Previous Text');
		
		$nextText = new Zend_Form_Element_Text('nextText');
		$nextText->setLabel('Next Text');
		
		$randomStart = new Zend_Form_Element_Select('randomStart');
		$randomStart->setLabel('Random Start');
		$randomStart->setMultiOptions($options);
		
		$finder = new Zend_Form_Element_Textarea('finder');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		
		$this->addElement($name)
			->addElement($isActive)
			->addElement($effect)
			->addElement($slices)
			->addElement($boxCols)
			->addElement($boxRows)
			->addElement($animSpeed)
			->addElement($pauseTime)
			->addElement($directionNav)
			->addElement($controlNav)
			->addElement($controlNavThumbs)
			->addElement($pauseOnHover)
			->addElement($manualAdvance)
			->addElement($prevText)
			->addElement($nextText)
			->addElement($randomStart)
			->addElement($finder)
			->addElement($submit);
	}
}