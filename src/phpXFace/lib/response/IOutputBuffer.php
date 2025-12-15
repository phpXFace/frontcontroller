<?php
namespace phpXFace\core\lib\response;

use phpXFace\core\lib\collection\IList;
use phpXFace\core\lib\collection\Map;

/**
 * Interface for managing an output buffer, providing methods to handle components,
 * plain text output, JSON output, and status management.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\response
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IOutputBuffer {

	/**
	 * @return \phpXFace\core\ui\viewport\common\IComponent
	 */
	public function getComponents();

	/**
	 * @param \phpXFace\core\ui\viewport\common\IComponent $components
	 */
	public function setComponents( $components );

	/**
	 * @param string $plainOutput
	 */
	public function setPlainOutput( $plainOutput );

	/**
	 * @param Map $map
	 */
	public function setJsonOutput( Map $map );

	/**
	 * @return Map
	 */
	public function getJsonOutput();

	/**
	 * @return string
	 */
	public function getPlainOutput();
    
    /**
     * @param number $status
     * @return void
     */
    public function setStatus( $status );
    
    /**
     * @return number
     */
    public function getStatus();
}
