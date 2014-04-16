<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * SearchWidget Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_SearchWidget extends System_Controller_Action_Helper_Widget
{

    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
        
        $this->data->message = 'Search is rendering';
    }
    public function preDispatch ()
    {
        // TODO Auto-generated method stub
        parent::preDispatch();
        $this->buildWidget();
    }
    public function buildWidget()
    {
       // assemble widget here
       //if(!$this->request->search) {
           $this->renderWidget($this->data); 
       //} else {
           $this->getForm();
           $this->renderWidget($this->data);
       //}
    }
    public function getForm()
    {
        $form = new Zend_Form();
        $form->setAction('/search/index');
        $form->setMethod('post');
        $text = new Zend_Form_Element_Text('search-text');
        $submit = new Zend_Form_Element_Submit('search');
        $submit->setLabel('Search');
        $form->addElements(array($text, $submit));
        $this->data->form = $form;
    }
	/**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
