<?php

class System_Form_Default extends Zend_Form
{

    public function init ($jscript = null)
    {
        $this->setMethod('post');
        
        $hash = new Zend_Form_Element_Hash('xcsrf', array(
                'salt' => 'unique'
        ));
        
        $this->addElement($hash);
        
        if (null !== $jscript) {
            self::addJava($jscript);
        }
    }
    protected function addSubmit($class = 'submit', $label = 'Submit', $name = 'submit')
    {
         $submit = new Zend_Form_Element_Submit($name);
         $submit->setLabel($label);
         $this->addElement($submit);
         return $this;
    }
    protected function addJava ($type)
    {
        $library = strtolower($type);
        
        if ($library === 'dojo') {
            Zend_Dojo::enableForm($this);
            if (count($this->getSubForms() > 0)) {
                foreach ($this->getSubForms() as $subform) {
                    Zend_Dojo::enableForm($subform);
                }
            }
        }
        if ($library === 'jquery') {
            if (! Zend_Loader::isReadable('/ZendX/jQuery/Form.php')) {
                return;
            } else {
                ZendX_JQuery::enableForm($this);
                if (count($this->getSubForms() > 0)) {
                    foreach ($this->getSubForms() as $subform) {
                        ZendX_JQuery::enableForm($subform);
                    }
                }
            }
        }
        return $this;
    }

}