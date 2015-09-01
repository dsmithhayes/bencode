<?php

namespace DSH\Bencode\Core;

interface Buffer
{
    /**
     * Reads in a raw value and stores it in the buffer.
     */
    public function read($value);
    
    /**
     * Returns the raw buffer.
     */
    public function write();
}
