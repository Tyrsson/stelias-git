<?php
class Pages_Filter_PageName implements Zend_Filter_Interface
{
    public $nameFilter;
    public $urlFilter;
    public $matches = array('/ /', '/_/', '/--/', '/&/', '/\'/', '/\"/');
    public $replacement = array('-', '-', '-', 'and', '', '');
    public $nameMatches = array('/&/');
    public $nameReplacement = array('and');
    
    
    public function __construct()
    {
        $this->urlFilter = new Zend_Filter();
        $this->nameFilter = new Zend_Filter();
         
         
        $entities = new Zend_Filter_HtmlEntities(array('quotestyle' => ENT_QUOTES));
        $trimFilter = new Zend_Filter_StringTrim();
        $alnumFilter = new Zend_Filter_Alnum(array('allowwhitespace' => true));
        $toLowerFilter = new Zend_Filter_StringToLower();
         
        $replaceUrlFilter = new Zend_Filter_PregReplace();
        $replaceUrlFilter->setMatchPattern($this->matches);
        $replaceUrlFilter->setReplacement($this->replacement);
         
        // build the chain
        $pageUrlFilter->addFilter($trimFilter);
        //$this->pageUrlFilter->addFilter($this->alnumFilter);
        $pageUrlFilter->addFilter($toLowerFilter);
        $pageUrlFilter->addFilter($replaceUrlFilter);
        //$this->pageUrlFilter->addFilter($this->alnumFilter);
         
         
        $pageNameFilter->addFilter($trimFilter);
        $replaceNameFilter = new Zend_Filter_PregReplace();
        $replaceNameFilter->setMatchPattern($this->nameMatches);
        $replaceNameFilter->setReplacement($this->nameReplacement);
        $pageNameFilter->addFilter($replaceNameFilter);
        //$this->pageNameFilter->addFilter($this->entities);
        $pageNameFilter->addFilter($alnumFilter);
    }
	/* (non-PHPdoc)
     * @see Zend_Filter_Interface::filter()
     */
    public function filter ($value)
    {
        // TODO Auto-generated method stub
    }
}