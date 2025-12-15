<?php
namespace phpXFace\core\lib\route;

use phpXFace\core\lib\annotations\PXF_DI as PXF_DI;
use phpXFace\core\lib\collection\HashMap;
use phpXFace\core\lib\collection\Map;
use phpXFace\core\lib\di\Injector;
use phpXFace\core\lib\resources\IResources;

/**
 * Class responsible for handling the allocation of routes based on resource type and route name.
 * It determines the existence of a route and evaluates its destination and parameters.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\route
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class RouteAllocation extends Injector implements IRouteAllocation{

	/**
	 * @PXF_DI("\phpXFace\core\lib\resources\Resources")
	 * @var IResources
	 */
	private $resources = null;

	/**
	 * @var array
	 */
	private $validRoute = null;

	/**
	 * @var null
	 */
	private $destination = null;

	/**
	 * @var Map
	 */
	private $params = null;

    public function getDestination() {
        return $this->destination;
    }

	public function routeExists( $routeName, $resourceType ){
		$exists = false;
		$routeRequest = explode('/', $routeName);
		$params = new HashMap();
		if( $this->resources->contains( $resourceType ) ){
			$routes = $this->resources->get( $resourceType );
			foreach ( $routeRequest as $index => $route ) {
				$exists = false;
				if( !$routes instanceof Map ){
					break; //no further routes found therefore no match
				}
				if( $routes->containsKey( $route ) ){
					$routes = $routes->get( $route );
					$exists = true;
				}else{
					foreach ( $routes as $key => $value ) {
						if ( $this->startsWith( $key, '{' ) && $this->endsWith( $key, '}' ) ) {
							$routes = $routes->get( $key );
							$placeholderSting = str_replace(['{', '}'], '', $key);
							$params->put( $placeholderSting, $route );
							$routeRequest[$index] = $key;
							$exists = true;
							break;
						}
					}
				}
				$this->evalDestination( $routes, count( $routeRequest ) === ( $index + 1 ));
			}
			$this->setValidRoute( $routeRequest );
			$this->setRouteParams( $params );
		}
		return $exists;
	}

	private function evalDestination( $routes, $isEOR ){
		if ( !$routes instanceof Map ) {
			$this->destination = $routes;
		} else if ( $isEOR && $routes->containsKey( '_path' ) ) {
			$this->destination = $routes->get( '_path' );
		}
	}

	/**
	 * @return null
	 */
	public function getValidRoute() {
		return $this->validRoute;
	}

	public function getParams() {
		return $this->params;
	}

	/**
	 * @param array $currentValidRoute
	 */
	private function setValidRoute( $currentValidRoute ) {
		$this->validRoute = $currentValidRoute;
	}

	/**
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	private function startsWith($haystack, $needle) {
		return strpos($haystack, $needle) === 0;
	}

	/**
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	private function endsWith($haystack, $needle) {
		return substr($haystack, -strlen($needle)) === $needle;
	}

	/**
	 * @param Map $routeParams
	 */
	private function setRouteParams( $routeParams ) {
		$this->params = $routeParams;
	}
}

?>
