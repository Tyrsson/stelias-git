<?php
class User_Form_ResetPass extends User_Form_Register
{
    public function init(){
        parent::init();
        
        $this->removeElement('firstName');
        $this->removeElement('lastName');
        $this->removeElement($name);
        $this->removeElement($name);
        $this->removeElement($name);
        
    }
}