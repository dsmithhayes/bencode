<?php

namespace Bencode;

use Bencode\Integer;
use Bencode\Byte;
use Bencode\Collection\BList;
use Bencode\Collection\Dictionary;
use Bencode\Core\Traits\FactoryBuild;

/**
 * Each Factory in this object can create an instance of the Element you
 * would want. You can pass either a raw-buffer value (integers for Integer,
 * strings for Bytes, etc) or build a closure that returns a value that can
 * be used as a buffer for the Element.
 */
class Bencode
{
    use FactoryBuild;
    
    /**
     * Returns an instance of an Integer.
     *
     * @param mixed   $value  Integer or closure
     * @param boolean $decode If true, uses `decode` method from Element
     * @return \Bencode\Integer
     */
    public static function integerFactory($value = null, $decode = false)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $integer = new Integer();
        
        return self::decodeOrRead($integer, $value, $decode);
    }
    
    /**
     * Returns an instance of a Byte
     *
     * @param mixed|null $value  String or closure
     * @param boolean    $decode If true, uses `decode` method from Element
     * @return \Bencode\Byte
     */
    public static function byteFactory($value = null, $decode = false)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $byte = new Byte();
        
        return self::decodeOrRead($byte, $value, $decode);
    }
    
    /**
     * Returns an instance of a BList element.
     *
     * @param mixed|null $value  A value, array, or closure
     * @param boolean    $decode If true, uses `decode` method from Element
     * @return \DSH\Bencode\Collection\BList
     */
    public static function blistFactory($value = null, $decode = false)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $blist = new BList();
        
        return self::decodeOrRead($blist, $value, $decode);
    }
    
    /**
     * Returns an instance of a Dictionary element.
     *
     * @param mixed|null $value  A value, array, or closure
     * @param boolean    $decode If true, uses `decode` method from Element
     * @return \Bencode\Collection\Dictionary
     */
    public static function dictionaryFactory($value = null, $decode = false)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $dictionary = new Dictionary();
        
        return self::decodeOrRead($dictionary, $value, $decode);
    }
}
