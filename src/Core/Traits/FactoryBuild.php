<?php

namespace DSH\Bencode\Core\Traits;

/**
 * Primarily used in the Bencode factory object, this Trait defines a method
 * that can be used to either `read` or `decode` a value into an object given.
 *
 * This method is *fail-safe* up to the point of throwing internal Exceptions
 * from each individual Element.
 */
trait FactoryBuild
{
    /**
     * Determines if the Element should `read` or `decode` the value.
     *
     * @param  Element  $element    The Element object
     * @param  mixed    $value      The value to put in the Object
     * @param  boolean  $decode     True to `decode`, false to `read`
     * 
     * @return Element  An Element object representing the value
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