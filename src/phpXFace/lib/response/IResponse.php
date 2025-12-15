<?php
namespace phpXFace\core\lib\response;

use phpXFace\core\ui\viewport\common\IComponent;

/**
 * Interface IResponse
 *
 * Provides methods for managing HTTP response headers, sending the response,
 * and setting associated components responsible for response handling.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\response
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IResponse {
    public function addHeader( $header );
    public function send( $statusCode = null);
    public function setComponents( IComponent $components );
}

?>
