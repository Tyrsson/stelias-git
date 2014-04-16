<?php
/**
 * System Dojo Build Layer and Profile support
 * writes builds and profiles specific for this application
 * used to optimize loading of dojo
 */
class System_Controller_Plugin_DojoLayer extends Zend_Controller_Plugin_Abstract
{
    public $adminLayer;
    public $publicLayer;
    public $buildProfile;
    protected $_build;
    protected $run = false;
    protected $section;

    public function preDispatch($request) {
        $request = $this->getRequest();
        if ($request->module === 'admin' && $request->controller === 'index' && $request->action === 'index') {
        	$this->run = true;
        	$this->section = 'admin';
        }
        elseif(!$request->module === 'admin' && $request->controller === 'index' && $request->action === 'index') {
            $this->run = true;
            $this->section = 'public';
        }
       // Zend_Debug::dump($this->run);
    }
    public function dispatchLoopShutdown()
    {
        $this->adminLayer = $_SERVER['DOCUMENT_ROOT'] . '/lib/admin/zendLayer.js';
        
        $this->publicLayer = $_SERVER['DOCUMENT_ROOT'] . '/lib/aurora/zendLayer.js';
        

        $this->buildProfile = APPLICATION_PATH . '/data/dojo/profiles/aurora.profile.js';
        if (!file_exists($this->adminLayer) && $this->run && $this->section === 'admin') {
            $this->generateDojoLayer($this->adminLayer);
        }
        if (!file_exists($this->publicLayer) && $this->run && $this->section === 'public') {
            $this->generateDojoLayer($this->publicLayer);
        }
        if (!file_exists($this->buildProfile)) {
            $this->generateBuildProfile();
        }

    }
    public function getBuild()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'ViewRenderer'
        );
        $viewRenderer->initView();
        if (null === $this->_build) {
            $this->_build = new Zend_Dojo_BuildLayer(array(
                    'view'      => $viewRenderer->view,
                    'layerName' => ($this->section === 'admin') ? 'admin.zendLayer' : 'aurora.zendLayer',
                    'consumeOnLoad' => true,
                    'consumeJavascript' => true
            ));
        }
        return $this->_build;
    }
    public function generateDojoLayer($layer)
    {
        $build = $this->getBuild();
        $layerContents = $build->generateLayerScript();
        if (!file_exists(dirname($layer))) {
            mkdir(dirname($layer));
        }
        file_put_contents($layer, $layerContents);
    }
    public function generateBuildProfile()
    {
        $build = $this->getBuild();
        $build->addProfileOption('version', 'aurora-1.5.1');
        $profile = $this->getBuild()->generateBuildProfile();
        file_put_contents($this->buildProfile, $profile);
    }

}