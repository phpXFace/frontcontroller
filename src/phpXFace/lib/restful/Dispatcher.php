<?php
namespace phpXFace\core\lib\restful;

use phpXFace\core\lib\annotations\PXF_DI as PXF_DI;
use phpXFace\core\lib\delegate\ServiceInvoker;
use phpXFace\core\lib\di\Injector;
use phpXFace\core\lib\eventdispatcher\EventDispatcher;
use phpXFace\core\lib\request\IRequestHandler;
use phpXFace\core\lib\route\IRouteManager;

/**
 * Class Dispatcher
 *
 * This class is responsible for handling the dispatching process within the framework.
 * It extends the Injector class and works closely with the IRouteManager and IRequestHandler
 * to manage routes and handle incoming requests.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\restful
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class Dispatcher extends Injector{

	/**
	 * @var IRouteManager
	 */
	private $routeManager = null;

	/**
	 * @PXF_DI("\phpXFace\core\lib\request\RequestHandler")
	 * @var IRequestHandler
	 */
	private $requestHandler = null;

	public function load(){
		$serviceInvoker = new ServiceInvoker();
		$serviceInvoker->invoke( $this->routeManager->getRoute() );
	}

	public function dispatch(){
		$eventDispatcher = EventDispatcher::getInstance();
		$requestType = null;

		if( $this->requestHandler->isPut() ){
			$requestType = 'PUT';
		}else if( $this->requestHandler->isDelete() ){
			$requestType = 'DELETE';
		}else if( $this->requestHandler->isGet() ){
			$requestType = 'GET';
		}else if( $this->requestHandler->isPost() ){
			$requestType = 'POST';
		}

		$eventDispatcher->trigger( $requestType . ':' . $this->routeManager->getRouteName(), $this->routeManager->getRequest()->getParameters() );
	}

	public function setRouteManager( IRouteManager $routeManager ) {
		$this->routeManager = $routeManager;
	}
} 
