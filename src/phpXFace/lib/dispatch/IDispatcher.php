<?php
namespace phpXFace\core\lib\dispatch;

/**
 * Interface IDispatcher
 *
 * Defines the contract for classes responsible for managing the dispatching
 * of application flow, including loading resources, handling routing, and
 * managing responses.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\dispatch
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IDispatcher {

    public function load();

    public function dispatch();
    
    public function setResponse( \phpXFace\core\lib\response\IResponse $response );
    
    public function setRouteManager( \phpXFace\core\lib\route\IRouteManager $routeManager );
}

?>
