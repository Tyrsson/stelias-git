<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Widget Action Helper
 *
 * @uses actionHelper Zend_Controller_Action_Helper
 */
abstract class System_Controller_Action_Helper_Widget extends Zend_Controller_Action_Helper_Abstract implements System_Controller_Action_Helper_WidgetInterface
{
    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    protected $view;
    public $data;
    public $appSettings = null;
    public $request;
    public $controller;
    public $currentAction;
    public $module;
    public $pageUrl;
    public $renderer;
    public $suffix;
    public $template;
    public $widgetName;
    public $isAjax = false;
    public $params;
    public $get;
    public $post;
    public $type;
    public $pages;
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct ()
    {
        // TODO Auto-generated Constructor
        $this->pluginLoader = new Zend_Loader_PluginLoader();


        if(Zend_Registry::isRegistered('appSettings'))
        {
            $this->appSettings = Zend_Registry::get('appSettings');
        }
        $this->data = new stdClass();
        $this->data->appSettings = $this->appSettings;
        $name = get_class($this);
        $filter = new Zend_Filter_Word_UnderscoreToSeparator('/');
        $path = $filter->filter($name);
        //Zend_Debug::dump($path);
        $filter = new Zend_Filter_BaseName();
        $className = $filter->filter($path);
        //Zend_Debug::dump($className);

        $this->widgetName = $className;
        //Zend_Debug::dump($this->widgetName, '$this->widgetName');
        $filter = new Zend_Filter_StringToLower();
        $this->template = $filter->filter($className);
        //Zend_Debug::dump($this->template, '$this->template');
        return $this;
    }
    public function setGet(&$get)
    {
        $this->get = $get;
    }
    public function setPost(&$post)
    {
        $this->post = $post;
    }
    public function preDispatch()
    {
        $this->request = $this->getRequest();
        $this->params = (object) $this->request->getParams();
        //Zend_Debug::dump($this->params);
        if($this->request->isXmlHttpRequest())
        {
            $this->isAjax = true;
        }
        $this->controller = $this->getActionController();
        $this->module = $this->request->getModuleName();
        $this->currentAction = $this->request->getActionName();
        $this->renderer = $this->controller->getHelper('viewRenderer');
        $this->view = $this->getView();
        $this->suffix = $this->renderer->getViewSuffix();
        $this->pageUrl = isset($this->request->pageUrl) ? $this->request->pageUrl : null;
        $this->pages = new Page_Model_Page();

        if($this->pageUrl !== null)
        {
            $this->page = $this->pages->fetchByUrl($this->pageUrl);
            try {
            	if($this->page == null) {
            		throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            	}
            } catch (Exception $e) {
            	return;
            }

            switch($this->page->pageType) {
                case strtolower($this->widgetName) :
                    call_user_func(array(get_called_class(), 'buildWidget'));
                    break;
                default :
                    return;
                    break;
            }

        }
    }
    public function getView()
    {
        if(null !== $this->view) {
            return $this->view;
        }
        $controller = $this->getActionController();
        $view = $controller->view;
        if(!$view instanceof Zend_View_Abstract) {
            return;
        }
        return $view;
    }
    abstract public function buildWidget();
    public function renderWidget(&$data, $renderFromModule = 'default') {
        $this->view->{strtolower($this->widgetName)} = $this->view->partial("$this->template.$this->suffix", $renderFromModule, $data);
    }
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
