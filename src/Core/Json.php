<?php

namespace DSH\Bencode\Core;

/**
 * Used with the Collection type Elements, this will return a JSON
 * enocded string of that Element's internal buffer.
 */

interface Json
{
    public function json();
}
