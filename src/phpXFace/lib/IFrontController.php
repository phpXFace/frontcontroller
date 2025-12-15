<?php
namespace phpXFace\core\lib;

/**
 * Interface representing a front controller responsible
 * for handling and delegating requests within an application.
 *
 * @author      C.Pergande
 * @package     phpXFace\core\lib
 * @copyright   Copyright(c) 2014 Christian Pergande - phpXFace®
 * @license     MIT
 */
interface IFrontController {
    
    /**
     * Run the front controller
     */
    public function run();
}

?>
