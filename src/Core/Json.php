<?php

namespace Bencode\Core;

/**
 * Used with the Collection type Elements, this will return a JSON enocded
 * string of that Element's internal buffer.
 */

interface Json
{
    /**
     * Creates a JSON encoding of the internal buffer of the element.
     *
     * @return string JSON formatted string
     */
    public function json();
}
