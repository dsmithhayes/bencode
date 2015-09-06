<?php

namespace DSH\Bencode\Collection;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Json;
use DSH\Bencode\Core\Traits\Pattern;
use DSH\Bencode\Byte;
use DSH\Bencode\Integer;
use DSH\Bencode\Exception\DictionaryException;

/**
 * The Dictionary class comes with some interesting rules. It is basically a
 * one-dimensional associative array to which each key is a byte.
 */
class Dictionary implements Element, Buffer, Json
{
    /**
     * For the dropEncoding() method.
     */
    use Pattern;
    
    /**
     * @const The regex pattern that matches an encoded Dictionary
     */
    const PATTERN = '/^d.*e$/';

    /**
     * @var array $_buffer the internal buffer array
     */
    protected $_buffer;

    /**
     * @var \DSH\Bencode\Byte
     */
    private $_key;

    /**
     * @var \DSH\Bencode\Byte
     */
    private $_value;

    /**
     * Initialize with an array or empty. What ever.
     *
     * @param array $buffer the array to become the buffer
     */
    public function __construct($buffer = [])
    {
        $this->read($buffer);
        $this->_key = new Byte();
        $this->_value = new Byte();
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
            $this->_key->read($key);
            $this->_value->read($val);

            $buffer .= ($this->_key->encode() . $this->_value->encode());
        }

        $buffer .= 'e';

        return $buffer;
    }

    /**
     * Decoding a Dictionary is a lot like decoding a List, however we are
     * looking for the firs two Elements and setting them as the key and value
     * of the array.
     *
     * @param string $stream Encoded Dictionary
     *
     * @throws \DSH\Bencode\Exception\DictionaryException
     *
     * @return string the remainder of the stream, if anything
     */
    public function decode($stream)
    {
        $stream = $this->dropEncoding($stream, self::PATTERN);

        if (!is_numeric($stream[0])) {
            throw new DictionaryException(
                'Improper stream encoding: ' . $stream
            );
        }

        // read the stream into a key and value, each are Byte objects
        $stream = $this->_key->decode($stream);
        $stream = $this->_value->decode($stream);

        // assign the key and value.
        $this->_buffer[$this->_key->write()] = $this->_value->write();

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
     * Indexed arrays will have their indices encoded as Bytes, otherwise pass
     * any array.
     *
     * @param array $value An array to become the Dictionary
     */
    public function read($value)
    {
        if (is_null($value)) {
            $value = [];
        }
        
        if (!is_array($value)) {
            throw new DictionaryException('Reading to buffer from non-array');
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

    /**
     * @return string The encoded Dictionary
     */
    public function __toString()
    {
        return $this->encode();
    }
}
