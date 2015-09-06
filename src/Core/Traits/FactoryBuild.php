<?php

namespace DSH\Bencode\Core\Traits;

/**
 * Primarily used in the Bencode factory object, this Trait defines a method
 * that can be used to either `read` or `decode` a value into an object given.
 */
trait FactoryBuild
{
    /**
     * Determines if the Element should `read` or `decode` the value.
     *
     * @param \DSH\Bencode\Core\Element $element The Element object
     * @param mixed                     $value   The value to put in the Object
     * @param boolean                   $decode  True to `decode`, false to
     *                                           `read`
     */
    public function decodeOrRead($element, $value, $decode)
    {
        if ($decode) {
            $element->decode($value);
        } else {
            $element->read($value);
        }
        
        return $element;
    }
}