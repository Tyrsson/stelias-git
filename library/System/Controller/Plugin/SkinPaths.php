<?php
class System_Controller_Plugin_SkinPaths extends Zend_Controller_Plugin_Abstract
{
	protected $defaultSkin = 'default';
	protected $scripts = 'scripts';
	protected $helpers = 'helpers';
	protected $view;
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {

		$this->getView();

		if($this->view->skinName !== $this->defaultSkin) {

			$viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
			//Zend_Debug::dump($viewRenderer->getViewBasePathSpec(), 'basepath spec');
			//Zend_Debug::dump($viewRenderer->getViewScript(), ' get view script');

// 			if(!file_exists( $viewRenderer->getViewBasePathSpec() . '/' . $this->scripts . '/' . $viewRenderer->getViewScript() ) ) {

// 				$viewRenderer->setViewBasePathSpec(APPLICATION_PATH .'/skins/'. $this->defaultSkin)
// 				->setViewScriptPathSpec(':module/:controller/:action.:suffix')
// 				->setViewScriptPathNoControllerSpec(':action.:suffix');

// 				Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
// 			}


		}
	}
	private function getView() {
		$front = Zend_Controller_Front::getInstance();
		$bootstrap = $front->getParam('bootstrap');
		$this->view = $bootstrap->getResource('view');
	}
}