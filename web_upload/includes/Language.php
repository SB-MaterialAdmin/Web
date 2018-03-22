<?php
/**
 * Language class.
 *
 * @package     GenericClasses
 * @subpackage  Classes
 * @version     1.2
 * @author      Kruzya <admin@crazyhackgut.ru>
 */

namespace Kruzya\Generic;

class Language {
    /**
     * Cache.
     *
     * @var array
     */
    private $cache;

    /**
     * Language dir.
     *
     * @var string
     */
    private $lang_dir;

    /**
     * User language.
     *
     * @var string
     */
    private $lang;

    /**
     * Constructor.
     *
     * @param   $lang_dir   Dir with languages.
     * @param   $lang       Language. Leave null if you don't using own system for detect language.
     * @throws  \Kruzya\Exceptions\FileOrDirNotFound
     * @return  void
     */
    public function __construct($lang_dir, $lang = null) {
        if (file_exists($lang_dir) && is_dir($lang_dir)) {
            $this->lang_dir = $lang_dir;
        } else {
            throw new Kruzya\Exceptions\FileOrDirNotFound($lang_dir);
        }

        $this->lang = $lang;
    }

    /**
     * Return a phrase.
     *
     * @param   $langPhrase Phrase.
     * @param   $formatArgs Args for formatting.
     * @throws  \Kruzya\Exceptions\LanguageCollectionNotFound
     * @return  string
     */
    public function retrieve($langPhrase, $formatArgs = array()) {
        $phrase = explode("::", $langPhrase);

        if (isset($this->cache[$phrase[0]]))
            return $this->getPhrase($langPhrase, $formatArgs);

        if (!$this->addPhrasesToCache($phrase[0]))
            throw new Kruzya\Exceptions\LanguageCollectionNotFound($phrase[0]);

        return $this->getPhrase($langPhrase, $formatArgs);
    }

    /**
     * Add phrases to cache.
     *
     * @param   $phraseCollection
     * @return  bool
     */
    private function addPhrasesToCache($phraseCollection) {
        $userLanguage = $this->retrieveUserLanguage();
        $phrCollectionPath = sprintf("%s%s_%s.lang", $this->lang_dir, $phraseCollection, $userLanguage);
        if (file_exists($phrCollectionPath)) {
            $this->cache[$phraseCollection] = json_decode(file_get_contents($phrCollectionPath), true);
            return true;
        }

        return false;
    }

    /**
     * Receives user language.
     *
     * @return  string
     */
    private function retrieveUserLanguage() {
        if ($this->lang !== NULL) {
            return $this->lang;
        }

        static $userLang = NULL;
        if ($userLang === NULL)
            $userLang = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if ($userLang == null)
            $userLang = "en";
        else {
            $userLang = explode("_", $userLang);
            $userLang = $userLang[0];
        }

        return $userLang;
    }

    /**
     * Receives a language phrase with formatting.
     *
     * @param   $lang_phrase    Language phrase.
     * @param   $format_args    Args for formatting string.
     * @return  string
     */
    private function getPhrase($lang_phrase, $format_args) {
        $lang_phrase = explode("::", $lang_phrase);
        return $this->_format($this->cache[$lang_phrase[0]][$lang_phrase[1]], $format_args);
    }

    /**
     * Formates a string.
     *
     * @param   $format_rules   String for formatting.
     * @param   $format_args    Arguments.
     * @return  string
     */
    private function _format($format_rules, $format_args) {
        $result = $format_rules;

        preg_match('/\{\{ (.{1,}) \}\}/', $format_rules, $matches, PREG_OFFSET_CAPTURE);
        unset($matches[0]);
        foreach ($matches as $match) {
            $token = trim($match[0]);
            $res = (isset($format_args[$token]) ?
                    $format_args[$token] :
                    "");

            $result = str_replace("{{ " . $token . " }}", $res, $result);
        }

        return $result;
    }
}
