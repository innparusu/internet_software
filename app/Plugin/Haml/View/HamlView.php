<?php
App::uses('View', 'View');

/**
 * Haml template engine for CakePHP
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 */
class HamlView extends View {

/**
 * View files extension
 */
  public static $extension = 'haml';

/**
 * Where the rendered CTP files will be stored
 */
  protected $_cacheFolder = 'views';

/**
 * Constructor
 */
  public function __construct(Controller $controller = null) {
    $this->Haml = new MtHaml\Environment('php', array('enable_escaper' => false));

    $controller->ext = '.' . self::$extension;

    return parent::__construct($controller);
  }

/**
 * Render the HAML template
 *
 * @param string $file The view file path
 *
 * @return string The rendered content
 */
  protected function _renderHaml($file) {
    return $this->Haml->compileString(file_get_contents($file), $file);
  }

/**
 * Generate a random cache file name
 *
 * @return string The cache file path
 */
  protected function _cacheFileName() {
    return CACHE . $this->_cacheFolder . DS . uniqid('haml_') . '.ctp';
  }

/**
 * Create the rendered view cache file
 *
 * @param string $file The view file path
 *
 * @return string The file path
 */
  protected function _createRenderedView($file) {
    $content = $this->_renderHaml($file);

    $tmpFile = new File($this->_cacheFileName(), true);
    $tmpFile->write($content);
    $tmpFile->close();

    return $tmpFile->pwd();
  }

/**
 * Delete the rendered cache file
 *
 * @return boolean
 */
  protected function _deleteRenderedView($tmpFile) {
    $file = new File($tmpFile);
    return $file->delete();
  }

/**
 * Evalute a view file, rendering it's contents
 *
 * @return string The output
 */
  protected function _evaluate($viewFile, $dataForView) {
    $file = new File($viewFile);

    if ($file->ext() != self::$extension) {
      return parent::_evaluate($viewFile, $dataForView);
    }

    $file = $this->_createRenderedView($viewFile);

    $content = parent::_evaluate($file, $dataForView);

    $this->_deleteRenderedView($file);

    return $content;
  }

}
