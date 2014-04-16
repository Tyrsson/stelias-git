<?php
class System_Controller_Dispatcher_Standard extends Zend_Controller_Dispatcher_Standard
{
	/**
	 * Dispatch to a controller/action
	 *
	 * By default, if a controller is not dispatchable, dispatch() will throw
	 * an exception. If you wish to use the default controller instead, set the
	 * param 'useDefaultControllerAlways' via {@link setParam()}.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @param Zend_Controller_Response_Abstract $response
	 * @return void
	 * @throws Zend_Controller_Dispatcher_Exception
	 */
	public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
	{
		$this->setResponse($response);

		/**
		 * Get controller class
		*/
		if (!$this->isDispatchable($request)) {
			$controller = $request->getControllerName();
			if (!$this->getParam('useDefaultControllerAlways') && !empty($controller)) {
				require_once 'Zend/Controller/Dispatcher/Exception.php';
				throw new Zend_Controller_Dispatcher_Exception('Invalid controller specified (' . $request->getControllerName() . ')');
			}

			$className = $this->getDefaultControllerClass($request);
		} else {
			$className = $this->getControllerClass($request);
			if (!$className) {
				$className = $this->getDefaultControllerClass($request);
			}
		}

		/**
		 * If we're in a module or prefixDefaultModule is on, we must add the module name
		 * prefix to the contents of $className, as getControllerClass does not do that automatically.
		 * We must keep a separate variable because modules are not strictly PSR-0: We need the no-module-prefix
		 * class name to do the class->file mapping, but the full class name to insantiate the controller
		 */
		$moduleClassName = $className;
		if (($this->_defaultModule != $this->_curModule)
		|| $this->getParam('prefixDefaultModule'))
		{
			$moduleClassName = $this->formatClassName($this->_curModule, $className);
		}

		/**
		 * Load the controller class file
		 */
		$className = $this->loadClass($className);

		/**
		 * Instantiate controller with request, response, and invocation
		 * arguments; throw exception if it's not an action controller
		*/
		$controller = new $moduleClassName($request, $this->getResponse(), $this->getParams());
		if (!($controller instanceof Zend_Controller_Action_Interface) &&
		!($controller instanceof Zend_Controller_Action)) {
			require_once 'Zend/Controller/Dispatcher/Exception.php';
			throw new Zend_Controller_Dispatcher_Exception(
					'Controller "' . $moduleClassName . '" is not an instance of Zend_Controller_Action_Interface'
			);
		}

		/**
		 * Retrieve the action name
		 */
		$action = $this->getActionMethod($request);

		/**
		 * Dispatch the method call
		*/
		$request->setDispatched(true);

		// by default, buffer output
		$disableOb = $this->getParam('disableOutputBuffering');
		$obLevel   = ob_get_level();
		if (empty($disableOb)) {

			$gzip = $this->getParam('useGzipBuffering');
			switch($gzip) {
				case true :
					ob_start('ob_gzhandler');
					break;
				case false :
					ob_start();
					break;
			}

		}

		try {
			$controller->dispatch($action);
		} catch (Exception $e) {
			// Clean output buffer on error
			$curObLevel = ob_get_level();
			if ($curObLevel > $obLevel) {
				do {
					ob_get_clean();
					$curObLevel = ob_get_level();
				} while ($curObLevel > $obLevel);
			}
			throw $e;
		}

		if (empty($disableOb)) {
			$content = ob_get_clean();
			$response->appendBody($content);
		}

		// Destroy the page controller instance and reflection objects
		$controller = null;
	}
}