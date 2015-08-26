<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Exceptions\BListException;

/**
 * The element list is a stream of encoded elements in a sequence.
 */
class BList implements Element, Buffer
{

    /**
     * @const PATTERN the regex pattern that matches a Byte
     */
    const PATTERN = '/^l.*e$/';

    /**
     * @var array An array of raw values that make up the list
     */
    protected $_buffer;

    public function __construct($buffer = [])
    {
        $this->read($buffer);
    }

    /**
     * Takes the List object's buffer and returns an encoded stream
     * that represents the byte.
     *
     * @return string Encoded Bencode list
     */
    public function encode()
    {
        $buffer = '';

        foreach ($this->_buffer as $b) {
            if (is_numeric($b)) {
                $int = new Integer($b);
                $buffer .= $int->encode();
            } else {
                $byte = new Byte($b);
                $buffer .= $byte->encode();
            }
        }

        return 'l' . $buffer . 'e';
    }

    /**
     * Reads a raw stream and decodes the List encoded portion
     * of it.
     *
     * @param string A raw encoded stream of a Bencode List.
     */
    public function decode($stream)
    {

    }

    /**
     * Returns the raw buffer of the List object.
     *
     * @return array The raw buffer of the List object
     */
    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Reads either an array to replace the buffer, or a value
     * to become the buffer. Objects without a `__toString()`
     * method may cause some problems. Integers can be passed
     * as strings and encoded as Integer objects.
     *
     * @param array|string Array to replace
     */
    public function read($value)
    {
        if (is_array($value)) {
            $this->_buffer = $value;
        } else {
            $this->_buffer[] = strval($value);
        }
    }
}
