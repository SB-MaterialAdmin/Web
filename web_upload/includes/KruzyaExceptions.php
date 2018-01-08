<?php
/**
 * Exceptions.
 *
 * @package     GenericClasses
 * @subpackage  Exceptions
 * @version     0.2 alpha
 * @author      Kruzya <admin@crazyhackgut.ru>
 */

namespace Kruzya\Exceptions;

class FileOrDirNotFound extends \Exception {
    /**
     * Trouble file.
     *
     * @var string
     */
    public $path;

    /**
     * Constructor.
     *
     * @param   $file   Trouble file.
     * @param   $code   Error code.
     *                  Default: 0.
     * @param   $prev   Previous exception.
     *                  Default: NULL.
     * @return  void
     */
    public function __construct($file, $code = 0, $prev = NULL) {
        parent::__construct("File or dir not found", $code, $prev);
        $this->path = $file;
    }
}

class LanguageCollectionNotFound extends \Exception {
    /**
     * Trouble phrase.
     *
     * @var string
     */
    public $collection_name;

    /**
     * Constructor.
     *
     * @param   $phrase Trouble phrase collection.
     * @param   $code   Error code.
     *                  Default: 0.
     * @param   $prev   Previous exception.
     *                  Default: NULL.
     * @return  void
     */
    public function __construct($phrase, $code = 0, $prev = NULL) {
        parent::__construct("Language collection with Phrases not found", $code, $prev);
        $this->collection_name = $phrase;
    }
}

class ModelException_RowExists extends \Exception {}
