<?php
namespace phpXFace\core\lib\route;

use phpXFace\core\lib\collection\Map;

/**
 * Interface IRouteAllocation
 *
 * Defines the contract for handling route allocation mechanisms, including
 * destination retrieval, route validation, and parameter management.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\route
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IRouteAllocation {

	/**
	 * @return string
	 */
    public function getDestination();

	/**
	 * @param $routeName
	 * @param $resourceType
	 * @return boolean
	 */
    public function routeExists( $routeName, $resourceType );

	/**
	 * @return array
	 */
	public function getValidRoute();

	/**
	 * @return Map
	 */
	public function getParams();
}

?>
