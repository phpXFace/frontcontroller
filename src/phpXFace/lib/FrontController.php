<?php
namespace phpXFace\core\lib;

use phpXFace\auth\client\models\IScope;
use phpXFace\core\lib\annotations\PXF_Property as PXF_Property;
use phpXFace\core\lib\annotations\PXF_DI as PXF_DI;
use phpXFace\core\lib\collection\IList;
use phpXFace\core\lib\di\InjectionSingelton;
use phpXFace\core\lib\di\Injector;
use phpXFace\core\lib\dispatch\IDispatcher;
use phpXFace\core\lib\eventdispatcher\EventDispatcher;
use phpXFace\core\lib\response\IResponse;
use phpXFace\core\lib\response\Response;
use phpXFace\core\lib\route\IRouteManager;
use phpXFace\core\lib\route\RouteAllocation;
use phpXFace\core\lib\route\RouteManager;
use phpXFace\core\lib\restful\Dispatcher as RestDispatcher;
use phpXFace\core\lib\dispatch\Dispatcher as ViewDispatcher;

/**
 * The FrontController class serves as the main entry point for handling HTTP requests,
 * managing routes, dispatching controllers, and implementing authentication logic.
 * It extends the Injector class and implements the IFrontController interface.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class FrontController extends Injector implements IFrontController {
	
	/**
	 * @var IResponse
	 */
	private $response = null;
	
	/**
	 * @var IDispatcher
	 */
	private $dispatcher = null;
	
	/**
	 * @var IRouteManager
	 */
	private $routeManager = null;
	
	/**
	 * @PXF_DI("phpXFace\auth\client\models\Scope")
	 * @var IScope
	 */
	private $scope = null;
	
	/**
	 * @PXF_Property("auth.saasrack.login.endpoint")
	 * @var string
	 */
	private $loginEndpoint = null;
	
	public function __construct() {
		parent::__construct();
		$this->routeManager = new RouteManager();
		$this->response = new Response();
		$this->routeManager->setRoutes( new RouteAllocation() );
		$this->routeManager->setup();
		
		if ( $this->routeManager->isRest() ) {
			$this->dispatcher = new RestDispatcher();
			$this->dispatcher->setRouteManager( $this->routeManager );
			$this->dispatcher->load();
		} else {
			$this->dispatcher = new ViewDispatcher();
			$this->dispatcher->setResponse( $this->response );
			$this->dispatcher->setRouteManager( $this->routeManager );
			$this->dispatcher->load();
		}
		
		$this->initAuth();
	}
	
	private function initAuth() {

		$supportedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
		
		if ( $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) {
			if ( isset( $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] ) && in_array($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'], $supportedMethods, true) ) {
				$this->response->addHeader( 'Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN'] );
				$this->response->addHeader( 'Access-Control-Allow-Methods: ' . implode(',', $supportedMethods) );
				$this->response->addHeader( 'Access-Control-Allow-Headers: origin, x-requested-with, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers, authorization' );
				$this->response->send();
			}
			exit( 0 );
		}
		
		if ( $this->routeManager->isRest() ) {
			$eventDispatcher = EventDispatcher::getInstance();
			$event = $eventDispatcher->getEvent( $_SERVER['REQUEST_METHOD'] . ':' . $this->routeManager->getRouteName() );
			$refClass = new \ReflectionClass( $event->getControllerInstance() );
			$refMethod = $refClass->getMethod( $event->getCallbackMethod() );
            $this->response->addHeader( 'Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN'] );
			$ScopeAnnotation = InjectionSingelton::getInstance()->getAnnotationReader()->getMethodAnnotation( $refMethod, "phpXFace\\core\\lib\\annotations\\PXF_SCOPE" );
			
			if ( $ScopeAnnotation && !$this->scope->isValid( $ScopeAnnotation->getScopeName() ) ) {
				$this->response->send(401);
			}
		} else if ( !$this->scope->isValid( $this->routeManager->getRouteName() ) ) {
			$protocol = stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) === true ? 'https://' : 'http://';
			$redirect = base64_encode( $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
			$this->response->addHeader( "Location: {$this->loginEndpoint}/{$redirect}" );
			$this->response->send();
		}
	}
	
	public function run() {
		$this->dispatcher->dispatch();
		$this->response->send();
	}
}

?>
