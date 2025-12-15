<?php
namespace phpXFace\core\lib\response;

use phpXFace\core\lib\annotations\PXF_DI as PXF_DI;
use phpXFace\core\lib\annotations\PXF_Property as PXF_Property;
use phpXFace\core\lib\asset\AssetReader;
use phpXFace\core\lib\di\Injector;
use phpXFace\core\lib\resources\IResources;
use phpXFace\core\ui\html\Body;
use phpXFace\core\ui\html\Head;
use phpXFace\core\ui\html\Html;
use phpXFace\core\ui\html\Link;
use phpXFace\core\ui\html\Meta;
use phpXFace\core\ui\html\Script;
use phpXFace\core\ui\viewport\common\IComponent;
use phpXFace\core\ui\viewport\common\IComposite;

/**
 * The Response class is responsible for managing HTTP responses, including
 * setting headers, handling output buffering, rendering components, and managing
 * related resources such as stylesheets and JavaScript files.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib\response
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
class Response extends Injector implements IResponse{

	/**
	 * @PXF_DI("\phpXFace\core\lib\resources\Resources")
	 * @var IResources
	 */
	private $resources = null;

	/**
	 * @PXF_DI("phpXFace\core\lib\response\OutputBuffer")
	 * @var IOutputBuffer
	 */
	private $outputBuffer = null;

	/**
	 * @PXF_Property("angularjs.rootscope")
	 * @var string
	 */
	private $angularJsRootScope = null;

	/**
	 * @var array
	 */
	protected $headers = [];

	/**
	 * @var IComponent
	 */
	protected $components = null;
    
	public function send( $statusCode = null) {
		if ( !headers_sent() ) {
			foreach( $this->headers as $header ) {
				header("$header", false);
			}
			
			if(!$statusCode){
				http_response_code($this->outputBuffer->getStatus());
			}else{
				http_response_code($statusCode);
			}
			
			if( !is_null( $this->outputBuffer->getPlainOutput() ) ){
				echo utf8_encode( $this->outputBuffer->getPlainOutput() );
			}else if( !is_null( $this->outputBuffer->getJsonOutput() ) ){
				header('Cache-Control: no-cache, must-revalidate');
				header('Content-type: application/json');
				echo utf8_encode( $this->outputBuffer->getJsonOutput()->toJson() );
			}else if( !is_null( $this->outputBuffer->getComponents() ) ){
				echo utf8_encode( $this->outputBuffer->getComponents()->draw() );
			}else if( !is_null( $this->components ) ){
				echo utf8_encode( $this->buildHtmlFrame() );
			}
			flush();
			exit;
		}
	}

	public function addHeader( $header ) {
		$this->headers[] = $header;
		return $this;
	}

	private function buildHtmlFrame(){

		/* AJAX check  */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			/* special ajax here */
			return $this->components->render()->render();
		}

		$head = new Head();
		$meta = new Meta();
		$meta->getAttributes()->addAttribute( 'name', "viewport" );
		$meta->getAttributes()->addAttribute( 'content', "width=device-width, initial-scale=1" );
		$head->add( $meta );

		//if( !$this->resources->contains( IResources::RESOURCE_TYPE_STYLESHEET ) || !$this->resources->contains( IResources::RESOURCE_TYPE_JAVASCRIPT ) ) {
			$assetReader = new AssetReader();
			$assetReader->copyCssToPublicPath();
			$assetReader->copyJsToPublicPath();
		//}

		if( $this->resources->contains( IResources::RESOURCE_TYPE_STYLESHEET ) ) {
			foreach( $this->resources->get( IResources::RESOURCE_TYPE_STYLESHEET ) as $css ) {
				$link = new Link();
				$link->getAttributes()->addAttribute( 'href', $css );
				$link->getAttributes()->addAttribute( 'type', 'text/css' );
				$link->getAttributes()->addAttribute( 'rel', 'stylesheet' );
				$head->add( $link );
			}
		}

		if( $this->resources->contains( IResources::RESOURCE_TYPE_JAVASCRIPT ) ) {
			foreach( $this->resources->get( IResources::RESOURCE_TYPE_JAVASCRIPT ) as $javascript ) {
				$script = new Script();
				$script->getAttributes()->addAttribute( 'src', $javascript );
				$head->add( $script );
			}
		}

		echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$html = new Html();
		$html->getAttributes()->addAttribute( 'xmlns', 'http://www.w3.org/1999/xhtml');
		$html->getAttributes()->addAttribute( 'xml:lang', 'en' );
		$html->getAttributes()->addAttribute( 'lang', 'en' );
		$html->getAttributes()->addAttribute( 'ng-app', $this->angularJsRootScope );
		$body = new Body();
		$body->add( $this->components->render() );
		$html->add( $head );
		$html->add( $body );
		return $html->render();

	}

	public function setComponents( IComponent $components ){
		if( is_null( $this->outputBuffer->getComponents() ) ){
			$this->components = $components;
		}
    }

}

?>
