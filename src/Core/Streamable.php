<?php

namespace DSH\Bencode\Core;

/**
 * The Streamable interface is super basic and uses one method to implement
 * a get/set of streamable data. Ideally you can take this and convert the
 * Bencoded elements to JSON or XML.
 */

interface Streamable
{
    public function stream($stream = null);
}
