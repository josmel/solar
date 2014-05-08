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
class ZExtraLib_Filter_ExtraTag
        extends Zend_Filter_Alnum
{
     public function __construct($allowWhiteSpace = false)
    {
    	parent::__construct();
    }
    public function filter($words)
    {
        return implode(' ',ZExtraLib_Utils::extractTag($words));
    }

}
