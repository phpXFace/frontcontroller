<?php
namespace phpXFace\core\lib\route;

/**
 * Interface defining the contract for managing routes within the application.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\route
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IRouteManager {
    
    /**
     * @return string
     */
    public function getRoute();

	/**
	 * @return string
	 */
	public function getRouteName();
    
    /**
     * @return \phpXFace\core\lib\request\IRequest
     */
    public function getRequest();
    
    /**
     * @param IRouteAllocation $routes
     */
    public function setRoutes( IRouteAllocation $routes );

	/**
	 * @return bool
	 */
	public function isRest();

	/**
	 * @return string
	 */
	public function getDefaultRoute();

	/**
	 * @param string $routeName
	 * @return void
	 */
	public function setRouteName( $routeName );
}

?>
