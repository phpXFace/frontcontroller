<?php
namespace phpXFace\core\lib\route;

use phpXFace\core\lib\annotations\PXF_Property as PXF_Property;
use phpXFace\core\lib\collection\HashMap;
use phpXFace\core\lib\collection\Map;
use phpXFace\core\lib\di\Injector;
use phpXFace\core\lib\request\IRequest;
use phpXFace\core\lib\request\Request;
use phpXFace\core\lib\resources\IResources;

/**
 * Manages routing functionalities and provides methods for route allocation and handling.
 * Handles route resolution, parameter application, and interactions with the routing system.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\route
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class RouteManager extends Injector implements IRouteManager{
    
    /**
     * @var \phpXFace\core\lib\route\IRouteAllocation
     */
    private $routes = null;
    
    /**
     * @var string
     */
    private $route = null;
    
    /**
     * @var string
     */
    private $routeName = null;

	/**
	 * @PXF_Property("route.default")
	 * @var string
	 */
	private $defaultRoute = null;
    
    /**
     * @var IRequest
     */
    private $request = null;

	/**
	 * @var bool
	 */
	private $isRest = false;
    
    public function __construct() {
	    parent::__construct();
        $this->request = new Request();
    }
    
    public function setup(){
	    $this->findRoute();
    }

	protected function findRoute(){

		if( !$this->routeExists() ) {
			header('HTTP/1.0 404 Not Found');
			echo '<h1>Error 404 Not Found</h1>';
			echo 'The page that you have requested could not be found.';
			exit();
		}

		$this->setRoute( $this->getRoutes()->getDestination() );
		$this->setRouteName( implode('/', $this->getRoutes()->getValidRoute() ) );
		$this->applyParameters( $this->getRoutes()->getParams() );
		$this->applyParameters( $this->preparePostRequestParameters() );
	}
    
    protected function applyParameters( Map $params ){
        if( $params->count() > 0 ){
            foreach( $params as $key => $value ) {
                $this->request->setParameter( $key, $value );
            }
        }
    }

	protected function preparePostRequestParameters(){
		$params = new HashMap();
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$jsonArray = json_decode( file_get_contents( 'php://input' ), true );
			if( $jsonArray ){
				foreach( $jsonArray as $key => $value ){
					if( is_array( $value ) ){
						$sanKey = htmlentities( $key );
						$sanValue = filter_var_array( $value, FILTER_SANITIZE_STRING );
					}else {
						$sanKey = htmlentities( $key );
						$sanValue = filter_var( $value, FILTER_SANITIZE_STRING );
					}
					$params->put( $sanKey, $sanValue );
				}
			}else{
				foreach( $_POST as $key => $value ){
					$sanKey   = htmlentities( $key );
					$sanValue = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
					$params->put( $sanKey, $sanValue );
				}
			}
		}

		if ( $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE' ) {
			$req = [];
			$jsonArray = json_decode( file_get_contents( 'php://input' ), true );

			if( $jsonArray ){
				$req = $jsonArray;
			}else{
				parse_str( file_get_contents('php://input' ), $req);
			}

			foreach( $req as $key => $value ){
				if( is_array( $value ) ){
					$sanKey = htmlentities( $key );
					//$sanValue = filter_var_array( $value, FILTER_SANITIZE_STRING );
					$sanValue = $value;
				}else {
					$sanKey = htmlentities( $key );
					$sanValue = filter_var( $value, FILTER_SANITIZE_STRING );
				}
				$params->put( $sanKey, $sanValue );
			}
		}
		return $params;
	}

    /**
     * @return \phpXFace\core\lib\route\IRouteAllocation
     */
    protected function getRoutes() {
        return $this->routes;
    }
    
    protected function setRoute( $route ) {
        $this->route = $route;
    }
    
    public function getRoute() {
        return $this->route;
    }

    public function setRoutes( IRouteAllocation $routes ) {
        $this->routes = $routes;
    }

    public function getRequest() {
        return $this->request;
    }
    
    public function getRouteName() {
        return $this->routeName;
    }

    public function setRouteName( $routeName ) {
        $this->routeName = $routeName;
    }

	public function isRest(){
		return $this->isRest;
	}

	public function getDefaultRoute() {
		return $this->defaultRoute;
	}

	private function routeExists(){
		$uri = trim( parse_url( \filter_input( \INPUT_SERVER , 'REQUEST_URI'), PHP_URL_PATH ), '/' );

		if( substr( $uri, 0, 5 ) === 'rest/' || substr( $uri, 0, 4 ) === 'api/' ) {
			$this->isRest = true;
			if( $this->getRoutes()->routeExists( $uri, IResources::RESOURCE_TYPE_SERVICES )){
				return true;
			}
		}

		if( $routeExists = $this->getRoutes()->routeExists( $uri, IResources::RESOURCE_TYPE_ROUTES ) ){
			return true;
		}

		if( $uri === '' && $this->getRoutes()->routeExists( $this->getDefaultRoute(), IResources::RESOURCE_TYPE_ROUTES ) ){
			return true;
		}

		return false;
	}

}

?>
