<?php

class Default_View_Helper_SlideShowHelper extends Zend_View_Helper_Abstract
{

	public $view;
	public $html;
	
	public function slideshowHelper()
	{
		//$this->view->headScript()->captureStart();
		
		//$this->view->headScript()->captureEnd();
	}

	/**
	 * Sets the view field
	 *
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

	
 }
