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
     * @param integer  $int The value to store in the Integer buffer
     * @param callable $cb  A callback function on the instantiated Integer.
     *
     * @return \DSH\Bencode\Integer An Integer object
     */
    public static function integerFactory($int = null, callable $cb = null)
    {
        $integer = new Integer($value);
        
        if ($cb) {
            $integer = $cb($integer);
        }
        
        return $intger;
    }
    
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
