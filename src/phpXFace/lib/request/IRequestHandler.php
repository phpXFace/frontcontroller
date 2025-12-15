<?php
namespace phpXFace\core\lib\request;

/**
 * Interface IRequestHandler
 *
 * Represents a contract for handling HTTP requests, with functionality to inspect headers,
 * detect request types, and configure response formats.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\request
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IRequestHandler {
	public function inspectHeader();
	public function setIsAjax( $isAjax );
	public function isAjax();
	public function isPut();
	public function isGet();
	public function isPost();
	public function isDelete();
	public function setIsHtml( $isHtml );
	public function acceptHtml();
	public function acceptJson( $isJson );
	public function getIsJson();
} 
