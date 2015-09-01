<?php

namespace DSH\Bencode\Core;

/**
 * Used with the Collection type Elements, this will return a JSON
 * enocded string of that Element's internal buffer.
 */

interface Json
{
    /**
     * Returns a JSON encoding of the internal buffer of the element.
     */
    public function json();
}
