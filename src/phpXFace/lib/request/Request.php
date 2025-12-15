<?php
namespace phpXFace\core\lib\request;

use phpXFace\core\lib\annotations\PXF_DI as PXF_DI;
use InvalidArgumentException;
use phpXFace\core\lib\collection\HashMap;
use phpXFace\core\lib\collection\Map;
use phpXFace\core\lib\di\Injector;

/**
 * Class Request
 *
 * Represents an HTTP request that encapsulates URI, parameters, and request handling logic.
 * Provides methods for managing request properties and validating the URI.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\request
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class Request extends Injector implements IRequest{

	/**
	 * @PXF_DI("\phpXFace\core\lib\request\RequestHandler")
	 * @var IRequestHandler
	 */
	private $requestHandler = null;

	/**
	 * @var string
	 */
	protected $uri = null;
    
    /**
     * @var Map
     */
    protected $parameters = null;
    
    public function __construct() {
	    parent::__construct();
        $this->parameters = new HashMap();
	    $this->requestHandler->inspectHeader();
    }
    
    public function getUri() {
        return $this->uri;
    }
    
    public function isValidUri(){
        if ( !filter_var( $this->uri, FILTER_VALIDATE_URL ) ) {
            throw new InvalidArgumentException("invalid uri");
        }
    }
    
    public function setParameter( $key, $value ) {
        $this->parameters->put( $key, $value );
    }
    
    public function getParameters() {
        return $this->parameters;
    }

	public function getRequestHandler(){
		return $this->requestHandler;
	}
}

?>
