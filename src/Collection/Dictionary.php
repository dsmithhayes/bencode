<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Json;
use DSH\Bencode\Byte;
use DSH\Bencode\Integer;
use DSH\Bencode\Exception\Dictionary;

/**
 * The Dictionary class comes with some interesting rules. It is
 * basically a one-dimensional associative array to which each
 * key is a byte.
 */

class Dictionary implements Element, Buffer
{
    const PATTERN = '/^d.*e$/';
    
    protected $_buffer;
    
    public function __construct($buffer = [])
    {
        $this->read($buffer);
    }

    public function encode()
    {

    }

    /**
     * Decoding a Dictionary is a lot like decoding a List, however
     * we are looking for the firs two Elements and setting them as
     * the key and value of the array.
     *
     * @param string $stream an encoded Dictionary
     */
    public function decode($stream)
    {

    }

    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Because of how Dictionaries are encoded, the read value can
     * either be a key-value array, or an associative array with the
     * first value being the key. All keys must be Bytes.
     * 
     * @param array $value Must be a key-value array.
     */
    public function read($value)
    {
        
    }

    public function json()
    {
        return json_encode($this->_buffer);
    }
}
