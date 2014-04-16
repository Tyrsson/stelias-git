<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';
/**
 * ContactWidget Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_Contact extends Zend_Controller_Action_Helper_Abstract
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
    protected $view;

    protected $_request;

    public function __construct ()
    {
        // TODO Auto-generated Constructor
        //$this->pluginLoader = new Zend_Loader_PluginLoader();
        $this->settings = Zend_Registry::get('appSettings');
        $this->pages = new Page_Model_Page();
    }
    public function preDispatch()
    {
        if (null === ($this->controller = $this->getActionController())) {
            return;
        }
        $this->renderer = $this->controller->getHelper('viewRenderer');

        $this->view = self::getView();
        $this->_request = $this->getRequest();
        // Only run this once our pageName is set
        if(isset($this->_request->pageUrl))
        {
            $this->page = $this->pages->fetchByUrl($this->_request->pageUrl);
            try {
            	if($this->page == null) {
            		throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            	}
            } catch (Exception $e) {
            	return;
            }
            switch($this->page->pageType) {
            	case 'contact' :
            		$this->handleContact();
            		break;
            	default :
            		return;
            		break;
            }

        }
    }
    public function handleContact() {

        $form = new Page_Form_Contact();
        $form->setAction($this->_request->pageUrl)->setMethod('post');
        if($this->_request->isPost())
        {
            if($form->isValid($this->_request->getPost())) {
                $namespace = $form->getElementsBelongTo();
                if (!empty($namespace) && !is_array($this->_request->getPost($namespace))) {
                    $this->renderContactForm($form);
                    return;
                }
                // mail handling
                $mail = new Zend_Mail();
                $this->post = $form->getValues($this->_request->getPost());
                //$result = $this->settings->fetchVar('siteEmail');
                $toEmail = $this->settings->siteEmail;
              	$mail->setFrom($this->post['email'], $this->post['name']);
                $message = $this->post['name'] . "\n" . $this->post['email'] . "\n" . $this->post['number'] . "\n" . $this->post['Editor'];

                $mail->setBodyText(strip_tags($message));

                $mail->addTo($toEmail);
                $mail->setSubject('Contact form submission');

                try {
                    $send = $mail->send();
                    if($send === true) {
                        $this->view->messages = array('Your email was sent successfully!');
                    } else {
                        $this->view->messages = array('There was an unknown error while trying to process your request.');
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }


            }
            elseif(!$form->isValid($this->_request->getPost())) {
                $this->renderContactForm($form);
                return;
            }

        } else {
            $this->renderContactForm($form);
        }
    }
    public function renderContactForm(Zend_Form $form, $error = null)
    {
       $this->view->contact = $this->view->partial('contactwidget.' . $this->renderer->getViewSuffix(), 'pages', array('contactForm' => $form, 'error' => $error));
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
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
