<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;

class Bencode
{
    private $element;
    
    public function __construct(Element $element)
    {
        $this->element = $element;
    }
}
