<?php

namespace Bencode\Core;

interface Element
{
    /**
     * Returns an encoded stream of the value in the buffer.
     */
    public function encode();
    
    /**
     * Reads a stream and extracts the valid encoding of an element. Should
     * always return the remainder of the stream, if any.
     */
    public function decode($stream);
}
