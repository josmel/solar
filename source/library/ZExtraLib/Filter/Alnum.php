<?php
/**
 * shortDescription
 *
 * longDescription
 *
 * @category   category
 * @package    package
 * @subpackage subpackage
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 */
class ZExtraLib_Filter_Alnum
        extends Zend_Filter_Alnum
{

    public function __construct($allowWhiteSpace = false)
    {
        parent::__construct($allowWhiteSpace);
    }

    /**
     * Funcion de Filtrado
     * @param paramType paramName paramDescription
     * @uses class::name()
     * @return returnType returnDescription
     */
    public function filter($value, $valuechange='', $min ='')
    {

        if (!self::$_unicodeEnabled) {
            // POSIX named classes are not supported, use alternative a-zA-Z0-9 match
            $pattern = '/[^a-zA-Z0-9]/';
        } else if (self::$_meansEnglishAlphabet) {
            //The Alphabet means english alphabet.
            $pattern = '/[^a-zA-Z0-9]/u';
        } else {
            //The Alphabet means each language's alphabet.
            $pattern = '/[^\p{L}\p{N}]/u';
        }
        if (trim($valuechange) != "") {
            $str = preg_replace('/\s\s+/', ' ',
                    preg_replace($pattern, ' ', $value));
            $replace = array("á", "à", "é", "è", "í", "ì", "ó", "ò", "ú", "ù", "ñ", "Ñ", "Á", "À", "É", "È", "Í", "Ì", "Ó", "Ò", "Ú", "Ù");
            $change = array("a", "a", "e", "e", "i", "i", "o", "o", "u", "u", "n", "N", "A", "A", "E", "E", "I", "I", "O", "O", "U", "U");
            $str = str_replace($replace, $change, $str);
            if ($min == 1)
                return str_replace(" ", $valuechange, strtoupper($str));
            if ($min == 0)
                return str_replace(" ", $valuechange, strtolower($str));
            if ($min == '')
                return str_replace(" ", $valuechange, $str);
        }
        else {
            return parent::filter($value);
        }
    }

}
