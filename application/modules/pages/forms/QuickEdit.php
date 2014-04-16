<?php
class Pages_Form_QuickEdit extends Zend_Dojo_Form
{
	/**
	 * Options to use with select elements
	 */
	protected $_selectOptions = array(
			'public'    => 'Public',
			'private'   => 'Private',
	);
	public $todayDate;

	/**
	 * Form initialization
	 *
	 * @return void
	*/
	public function init()
	{
		$this->setMethod('post');
		$this->setAttribs(array(
				'name'  => 'quickEdit',
		));
		$this->setDecorators(array(
				'FormElements',
				array('TabContainer', array(
						'id' => 'tabContainer',
						'style' => 'width: 600px; height: 500px;',
						'dijitParams' => array(
								'tabPosition' => 'top'
						),
				)),
				'DijitForm',
		));
		$editorForm = new Zend_Dojo_Form_SubForm();
		$editorForm->setAttribs(array(
				'name'   => 'editortab',
				'legend' => 'Editor',
				'dijitParams' => array(
									'title' => 'Editor'
									  ),
				)
		)
		->addElement(
				'Editor',
				'pageText',
				array(
						'label'        => 'Editor',
						'inheritWidth' => 'true',
				)
		);
		$selectForm = new Zend_Dojo_Form_SubForm();
		$selectForm->setAttribs(array(
				'name'   => 'optionstab',
				'legend' => 'Page Options',
		));
		$selectForm->addElement(
				'ComboBox',
				'pageName',
				array(
						'label' => 'Select PageName',
						'storeId' => 'nameStore',
						'storeType' => 'dojo.data.ItemFileReadStore',
						'storeParams' => array(
								'url' => '/pages/json/pagenamestore',
						),
						'dijitParams' => array(
								'searchAttr' => 'name',
						),
				)
		)
		->addElement(
				'NumberTextBox',
				'pageOrder',
				array(
						'label' => 'Page Menu Order',
						'required'  => true,
						'invalidMessage' => 'Page Order out of range',
						'constraints' => array(
								'min' => 0,
								'max' => 50,
								'places' => 0,
						),
				)
		)
		->addElement(
				'ComboBox',
				'role',
				array(
						'label' => 'Page Access Role',
						'storeId' => 'roleStore',
						'storeType' => 'dojo.data.ItemFileReadStore',
						'storeParams' => array(
								'url' => '/pages/json/rolestore',
						),
						'dijitParams' => array(
								'searchAttr' => 'role',
						),
				)
		)
		->addElement(
				'ComboBox',
				'visibility',
				array(
						'label' => 'Page Visibility (select)',
						'value' => 'public',
						'autocomplete' => false,
						'multiOptions' => $this->_selectOptions,
				)
		)
		->addElement(
				'DateTextBox',
				'createdDate',
				array(
						'value' => $this->getTodayDate(),
						'label' => 'Created On',
						'required'  => false,
				)
		)
		->addElement(
				'DateTextBox',
				'publishDate',
				array(
						'value' => $this->getTodayDate(),
						'label' => 'Publish Date',
						'required'  => false,
				)
		)
		->addElement(
				'DateTextBox',
				'modifiedDate',
				array(
						'value' => $this->getTodayDate(),
						'label' => 'Last Modified Date',
						'required'  => false,
				)
		);

		$submitForm = new Zend_Dojo_Form_SubForm();
		$submitForm->setAttribs(array('name' => 'submittab',
									  'legend' => 'Submit Edits'));
		$submitForm->addElement('SubmitButton', 'Submit');

		$this->addSubForm($editorForm, 'editortab')->addSubForm($selectForm, 'optionstab')->addSubForm($submitForm, 'submittab');
	}
	public function getTodayDate()
	{
		$date = new Zend_Date();
		$today = $date->toString('yyyy-MM-dd');
		$this->todayDate = $today;
		return $this->todayDate;
	}
}