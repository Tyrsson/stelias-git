<?php
class Festival_Form_MenuItems extends Zend_Form
{
	public function init()
	{
	    $this->setAttrib('id', 'manageMenuItems');
	    $this->setMethod('post');
	    
	    
	    $itemId = new Zend_Form_Element_Hidden('itemId');
	    $menuId = new Zend_Form_Element_Hidden('menuId');
	    $cmd = new Zend_Form_Element_Hidden('cmd');
	    
	    $itemType = new Zend_Form_Element_Select('itemType');
	    $itemType->setLabel('Item Type');
	    $itemType->setMultiOptions(array('none' => '', 'plate' => 'Plate', 'carte' => 'A-La-Carte', 'dessert' => 'Dessert', 'drink' => 'Drink'));
	    
	    $pickupOnly = new Zend_Form_Element_Checkbox('pickupOnly');
	    $pickupOnly->setCheckedValue(1);
	    $pickupOnly->setChecked(true);
	    $pickupOnly->setLabel('Pickup Only Item?');

// 	    $this->addElement(
// 	    	'Checkbox',
// 	        'pickupOnly',
// 	        array(
// 	    	'label' => 'Pickup Only',
// 	        'checkedValue' => 1,
// 	        'uncheckedValue' => 0,
// 	        'checked' => true
// 	    )
// 	    );
	    
	    $itemName = new Zend_Form_Element_Text('itemName');
	    $itemName->setLabel('Item Name')->setRequired(true);
	    
	    $itemDesc = new Zend_Form_Element_Textarea('itemDesc');
	    $itemDesc->setLabel('Item Description');
	    $itemDesc->setAttribs(array('cols' => '50', 'rows' => '5'));
	    
	    $price = new Zend_Form_Element_Text('price');
	    $price->setLabel('Price')->setRequired(true);
	    $price->setAttribs(array('size' => '5'));
	    
	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setAttribs(array('id' => 'addmenuitem'));
	    //$submit->setLabel('Submit');
	    
	    $this->addElements(array($itemId, $menuId, $cmd, $itemType, $pickupOnly, $itemName, $itemDesc, $price, $submit));
	}
}
