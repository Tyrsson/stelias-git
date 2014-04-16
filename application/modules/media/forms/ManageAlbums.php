<?php
class Media_Form_ManageAlbums extends Zend_Form
{
    public function init()
    {
        $finder = new Zend_Form_Element_Textarea('finder');
        $finder->setRequired(true);

        $this->addElement($finder);
    }
}