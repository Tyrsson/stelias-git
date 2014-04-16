<?php
class Search_Form_QuickSearch extends Zend_Form
{
    public function init() {
        $this->setAction('/search/results');
        $this->setMethod('get');
        $this->setName('quicksearch');
        $text = new Zend_Form_Element_Text('term');
        $submit = new Zend_Form_Element_Submit('search');
        $submit->setLabel('Search');
        $this->addElements(array($text, $submit));
    }
}