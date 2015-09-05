<?php

namespace DSH\Bencode;

use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Collection\BList;
use DSH\Bencode\Collection\Dictionary;

class Bencode
{
    /**
     * Returns an instance of an Integer.
     *
     * @param int|null      $int The value to store in the Integer buffer
     * @param callable|null $cb  A callback function on the instantiated Integer
     *
     * @return \DSH\Bencode\Integer An Integer object
     */
    public static function integerFactory($int = null, callable $cb = null)
    {
        $integer = new Integer($int);
        
        if ($cb) {
            $integer = $cb($integer);
        }
        
        return $integer;
    }
    
    /**
     * Returns an instance of a Byte
     *
     * @param string|null   $byte The stream to represent a Byte
     * @param callable|null $cb   A callback function on the instantiated Byte
     *
     * @return \DSH\Bencode\Byte A Byte object
     */
    public static function byteFactory($byte = null, callable $cb = null)
    {
        $byte = new Byte($value);
        
        if ($cb) {
            $byte = $cb($byte);
        }
        
        return $byte;
    }
    
    public static function blistFactory($blist = null, callable $cb = null)
    {
        $blist = new BList($blist);
        
        if ($cb) {
            $blist = $cb($blist);
        }
        
        return $blist;
    }
    
    public static function dictionaryFactory($dict = null, callable $cb = null)
    {
        $dictionary = new Dictionary($dict);
        
        if ($cb) {
            $dictionary = $cb($dictionary);
        }
        
        return $dictionary;
    }
}
