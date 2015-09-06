<?php

namespace DSH\Bencode;

use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Collection\BList;
use DSH\Bencode\Collection\Dictionary;

/**
 * Each Factory in this object can create an instance of the Element you
 * would want. You can pass either a raw-buffer value (integers for Integer,
 * strings for Bytes, etc) or build a closure that returns a value that can
 * be used as a buffer for the Element.
 */
class Bencode
{
    /**
     * Returns an instance of an Integer.
     *
     * @param mixed $value Integer or closure
     * @return \DSH\Bencode\Integer
     */
    public static function integerFactory($value = null)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $integer = new Integer($value);
        
        return $integer;
    }
    
    /**
     * Returns an instance of a Byte
     *
     * @param mixed $value String or closure
     * @return \DSH\Bencode\Byte
     */
    public static function byteFactory($value = null)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $byte = new Byte($value);
        
        return $byte;
    }
    
    /**
     * Returns an instance of a BList element.
     *
     * @param mixed|null $value A value, array, or closure
     * @return \DSH\Bencode\Collection\BList
     */
    public static function blistFactory($value = null)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $blist = new BList($value);
        
        return $blist;
    }
    
    /**
     * Returns an instance of a Dictionary element.
     *
     * @param mixed|null $value A value, array, or closure
     * @return \DSH\Bencode\Collection\Dictionary
     */
    public static function dictionaryFactory($value = null)
    {
        if (is_callable($value)) {
            $value = $value();
        }
        
        $dictionary = new Dictionary($value);
        
        return $dictionary;
    }
}
