<?php
namespace phpXFace\core\lib\dispatch;

use phpXFace\core\lib\eventdispatcher\EventDispatcher;
use phpXFace\core\lib\response\IResponse;
use phpXFace\core\lib\route\IRouteManager;
use phpXFace\core\PXFLoader;
use phpXFace\core\ui\viewport\common\AbstractComponentBase;

/**
 * Handles the dispatching of application routes and coordinates the loading and response generation processes.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\dispatch
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class Dispatcher implements IDispatcher{

    /**
     * @var IRouteManager
     */
    private $routeManager = null;
    
    /**
     * @var IResponse
     */
    private $response = null;

    /**
     * @var AbstractComponentBase
     */
    private $components = null;

    public function load(){
        $pxfLoader = new PXFLoader();
        $this->components = $pxfLoader->load( $this->routeManager->getRoute() );
    }
    
    public function dispatch(){
        $eventDispatcher = EventDispatcher::getInstance();
        $eventDispatcher->trigger( $this->routeManager->getRouteName(), $this->routeManager->getRequest()->getParameters() );
        $this->response->setComponents( $this->components );
    }
    
    public function setResponse( IResponse $response ) {
        $this->response = $response;
    }
    
    public function setRouteManager( IRouteManager $routeManager ) {
        $this->routeManager = $routeManager;
    }
    
}

?>
