<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Json;
use DSH\Bencode\Byte;
use DSH\Bencode\Integer;
use DSH\Bencode\Exception\Dictionary;

/**
 * The Dictionary class comes with some interesting rules. It is
 * basically a one-dimensional associative array to which each
 * key is a byte.
 */

class Dictionary implements Element, Buffer, Json
{
    /**
     * @const The regex pattern that matches an encoded Dictionary
     */
    const PATTERN = '/^d.*e$/';

    /**
     * @var array $_buffer the internal buffer array
     */
    protected $_buffer;

    /**
     * Initialize with an array or empty. What ever.
     *
     * @param array $buffer the array to become the buffer
     */
    public function __construct($buffer = [])
    {
        $this->read($buffer);
    }

    /**
     * Returns an encoded stream of a Dictionary element.
     *
     * @return string Encoded Dictionary
     */
    public function encode()
    {
        $buffer = 'd';
        
        foreach ($this->_buffer as $key => $val) {
            $key = new Byte($key);
            $val = new Byte($val);

            $buffer .= (key->encode() . $val->encode());
        }

        $buffer .= 'e';

        return $buffer;
    }

    /**
     * Decoding a Dictionary is a lot like decoding a List, however
     * we are looking for the firs two Elements and setting them as
     * the key and value of the array.
     *
     * @param string $stream Encoded Dictionary
     */
    public function decode($stream)
    {
        if (preg_match(self::PATTERN, $stream) {
            $stream = substr($stream, 1);
            $stream = substr($stream, 0, -1);
        }

        $stream = str_split($stream);

        // decode the bytes, build the array

        if (strlen($stream) > 0) {
            return $this->decode($stream);
        }

        return $stream;
    }

    /**
     * Gets the internal buffer of the Dictionary object.
     *
     * @return array Internal buffer
     */
    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Indexed arrays will have their indices encoded as Bytes,
     * otherwise pass any array.
     * 
     * @param array $value An array to become the Dictionary
     */
    public function read($value)
    {
        if (!is_array($value)) {
            throw new DictionaryException('reading from non-array');
        }

        // set as the buffer
        $this->_buffer = $value;
    }

    /**
     * Encodes the internal buffer as JSON.
     *
     * @return string JSON encoded internal buffer
     */
    public function json()
    {
        return json_encode($this->_buffer);
    }
}
