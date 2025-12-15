<?php
namespace phpXFace\core\lib\request;

/**
 * Interface IRequest
 *
 * Represents a request handling interface that provides methods to
 * manage and retrieve information about a request, such as its URI,
 * validation, parameters, and request handler.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\request
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IRequest {
    public function getUri();
    public function isValidUri();
    public function setParameter( $key, $value );
    public function getParameters();
	/**
	 * @return IRequestHandler
	 */
	public function getRequestHandler();
}

?>
