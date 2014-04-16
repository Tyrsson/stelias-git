<?php
class Festival_Form_Order extends Zend_Form
{
	public function addRow()
	{
		$row_form = new Zend_Form(array(
			'elements' => array(
				'item' => array(
					'type' => 'textarea'
				),
				'price' => array(
					'type' => 'text'
				),
				'amount' => array(
					'type' => 'text'
				),
		        'linetotal' => array(
		                'type' => 'text'
		        ),
			),
			'decorators' => array('FormElements', array('HtmlTag', array('tag'=>'tr'))),
			'elementDecorators' => array('ViewHelper', array('HtmlTag', array('tag'=>'td')))
		));

		$new_form_index = count($this->_subForms)+1;
		$row_form->setElementsBelongTo($new_form_index);
		$this->addSubform($row_form, $new_form_index);

		return $row_form;
	}
	
	public function init()
	{
		$this->setOptions(array(
			'elements' => array(
				'submit' => array(
					'type' => 'submit',
				)
			),
		));

		$this->addPrefixPath('Dxcore_Form_Decorator', 'Dxcore/Form/Decorator/', 'Decorator');
		$this->setDecorators(array('FormElements', array('SimpleTable', array('columns' => array('Item', 'Price', 'Amount', 'Total'))), 'Form'));

		$row1 = $this->addRow();
		$row1->getElement('price')->setAttribs(array('disabled' => 'disabled', 'size' => '5'));
		$text = 'just some test text just some test text just some test text';
		$row1->setDescription($text);
		
		$cols = strlen(utf8_decode($text));
		$row1->getElement('item')->setAttribs(array('disabled' => 'disabled', 'cols' => $cols, 'rows' => 2));
		$row1->populate(array('item' => 'just some test text just some test text just some test text', 'price' => '16.00', 'amount' => '', 'total' => ''));
		
		$this->addRow();
		$this->addRow();
		$this->addRow();
		$this->addRow();
		
		$this->getElement('submit')->setDecorators(array('ViewHelper', array(array('td'=>'HtmlTag'), array('tag'=>'td', 'colspan'=>3)), array(array('tr'=>'HtmlTag'), array('tag'=>'tr'))));
		$this->getElement('submit')->setOrder(100);
	}
}