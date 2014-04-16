<?php
class System_Controller_Plugin_ComingSoon extends Zend_Controller_Plugin_Abstract
{
    private $allowedIps = array('98.71.67.175', '24.179.4.69', '99.71.180.90');
    private $ip;
    private $allow = false;
    protected  $bootstrap;
    public  $view;
    public  $options;
    public  $request;
    
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->request = $request;
        try {
            // get the bootstrap
            $this->bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
            // Get the server proxy for $_SERVER[$index]
            $server = self::getServer();
            $this->ip = $server['REMOTE_ADDR'];
            
            if(in_array($this->ip, $this->allowedIps))
            {
                $this->allow = true;
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function preDispatch()
    {
        if($this->request->getActionName() === 'comingsoon')
        {
            return;
        } else {
            if(!$this->allow)
            {
                $this->request->setActionName('comingsoon');
            }
        }
    }
    protected function getHttp()
    {
        $this->http = new Zend_Controller_Request_Http();
        return $this;
    }
    protected function getServer()
    {
        self::getHttp();
        $this->server = $this->http->getServer();
        return $this->server;
    }
	protected function getView()
	{
		$this->view = $this->bootstrap->getResource('view');
		return $this->view;
	}
}