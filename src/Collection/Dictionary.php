<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Json;
use DSH\Bencode\Byte;
use DSH\Bencode\Integer;
use DSH\Bencode\Exception\Dictionary;

class Dictionary implements Element, Buffer
{
    const PATTERN = '/^d.*e$/';
    
    protected $_buffer;
    
    public function __construct($buffer = [])
    {
        $this->_buffer = [];
    }

    public function encode()
    {

    }

    public function decode($stream)
    {

    }

    public function write()
    {

    }

    public function read($value)
    {

    }

    public function json()
    {
        return json_encode($this->_buffer);
    }
}
