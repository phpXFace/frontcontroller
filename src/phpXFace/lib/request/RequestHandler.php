<?php
namespace phpXFace\core\lib\request;

/**
 * Handles requests by inspecting headers and determining the request type.
 * Implements the IRequestHandler interface.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\request
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class RequestHandler implements IRequestHandler{

	/**
	 * @var boolean
	 */
	private $isJson = false;

	/**
	 * @var boolean
	 */
	private $isHtml = true;

	/**
	 * @var boolean
	 */
	private $isAjax = false;

	/**
	 * @var bool
	 */
	private $isGet = false;

	/**
	 * @var bool
	 */
	private $isPost = false;

	/**
	 * @var bool
	 */
	private $isPut = false;

	/**
	 * @var bool
	 */
	private $isDelete = false;

	public function inspectHeader(){
		/* AJAX check  */
		if( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
			$this->setIsAjax( true );
		}
		if( $_SERVER['REQUEST_METHOD'] === 'PUT' ) {
			$this->isPut = true;
		}

		if( $_SERVER['REQUEST_METHOD'] === 'DELETE' ) {
			$this->isDelete = true;
		}

		if( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
			$this->isGet = true;
		}

		if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$this->isPost = true;
		}
	}

	/**
	 * @param boolean $isAjax
	 */
	public function setIsAjax( $isAjax ) {
		$this->isAjax = $isAjax;
	}

	/**
	 * @return boolean
	 */
	public function isAjax() {
		return $this->isAjax;
	}

	/**
	 * @param boolean $isHtml
	 */
	public function setIsHtml( $isHtml ) {
		$this->isHtml = $isHtml;
	}

	/**
	 * @return boolean
	 */
	public function acceptHtml() {
		return $this->isHtml;
	}

	/**
	 * @param boolean $isJson
	 */
	public function acceptJson( $isJson ) {
		$this->isJson = $isJson;
	}

	/**
	 * @return boolean
	 */
	public function isJson() {
		return $this->isJson;
	}

	/**
	 * @return boolean
	 */
	public function isDelete() {
		return $this->isDelete;
	}

	/**
	 * @return boolean
	 */
	public function isPost() {
		return $this->isPost;
	}

	/**
	 * @return boolean
	 */
	public function isGet() {
		return $this->isGet;
	}

	/**
	 * @return boolean
	 */
	public function isPut() {
		return $this->isPut;
	}

	public function getIsJson() {
		return $this->isJson;
	}

}
