<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Json;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Exception\BListException;

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
                $element = new Integer($b);
            } else {
                $element = new Byte($b);
            }

            $buffer .= $element->encode();
        }

        return 'l' . $buffer . 'e';
    }

    /**
     * Reads a raw stream and decodes the List encoded portion
     * of it. This will append data to the internal buffer.
     *
     * @param string A raw encoded stream of a Bencode List.
     * @throws \DSH\Bencode\Exception\BListException;
     */
    public function decode($stream)
    {
        // drop the beginning and end if they exist
        if (preg_match(self::PATTERN, $stream)) {
            $stream = substr($stream, 1);
            $stream = substr($stream, 0, -1);
        }

        // This block determines which type of primitive element is
        // in the list
        if (is_numeric($stream[0])) {
            $element = new Byte();
        } elseif ($stream[0] === 'i') {
            $element = new Integer();
        } else {
            throw new BListException('Decoded improper encoding: ' . $stream);
        }

        $stream = $element->decode($stream);
        $this->_buffer[] = $element->write();

        if (strlen($stream) > 0) {
            return $this->decode($stream);
        }
    }

    /**
     * Returns the raw buffer of the List object. In this case, its
     * just and array of data.
     *
     * @return array The raw buffer of the List object
     */
    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Reads either an array to replace the buffer, or a value to
     * become the buffer. Objects without a `__toString()` method
     * may cause some problems. Integers can be passed as strings
     * and encoded as Integer objects.
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

    /**
     * Returns a JSON encoding of the List.
     *
     * @return string A JSON encoded string.
     */
    public function json()
    {
        return json_encode($this->_buffer);
    }

    /**
     * @return string The encoded List
     */
    public function __toString()
    {
        return $this->encode();
    }
}
