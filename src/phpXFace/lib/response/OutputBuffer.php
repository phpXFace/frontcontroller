<?php
namespace phpXFace\core\lib\response;

use phpXFace\core\lib\collection\IList;
use phpXFace\core\lib\collection\Map;
use phpXFace\core\ui\viewport\common\IComponent;

/**
 * Represents an output buffer for managing and retrieving data in different formats.
 * Provides functionality to store and retrieve components, plain text output,
 * JSON output, and HTTP status codes.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\response
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class OutputBuffer implements IOutputBuffer {

	/**
	 * @var IComponent
	 */
	private $components = null;

	/**
	 * @var string
	 */
	private $plainOutput = null;

	/**
	 * @var Map
	 */
	private $jsonOutput = null;
    
    /**
     * @var number
     */
    private $status = 200;

	/**
	 * @param \phpXFace\core\ui\viewport\common\IComponent $components
	 */
	public function setComponents( $components ) {
		$this->components = $components;
	}

	/**
	 * @return \phpXFace\core\ui\viewport\common\IComponent
	 */
	public function getComponents() {
		return $this->components;
	}

	/**
	 * @param string $plainOutput
	 */
	public function setPlainOutput( $plainOutput ) {
		$this->plainOutput = $plainOutput;
	}

	/**
	 * @param Map $map
	 */
	public function setJsonOutput( Map $map ){
		if( !$map->containsKey( 'status' ) ){
			$map->put( 'status', 'success' );
		}
		$this->jsonOutput = $map;
	}

	/**
	 * @return Map
	 */
	public function getJsonOutput() {
		return $this->jsonOutput;
	}

	/**
	 * @return string
	 */
	public function getPlainOutput() {
		return $this->plainOutput;
	}
    
    /**
     * @param number $status
     * @return void
     */
    public function setStatus( $status ){
        $this->status = $status;
    }
    
    /**
     * @return number
     */
    public function getStatus(){
        return $this->status;
    }

} 
