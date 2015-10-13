<?php

namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Exception\ByteException;

class Byte implements Element, Buffer
{
    /**
     * @const PATTERN Represents the regex for an encoded byte.
     */
    const PATTERN = '/\d+:\w+/';

    /**
     * @var string A raw byte buffer.
     */
    protected $_buffer;

    /**
     * @param string $buffer a raw byte.
     */
    public function __construct($buffer = '')
    {
        $this->read($buffer);
    }

    /**
     * Returns an encoded byte from the raw buffer.
     *
     * @return string The encoded byte.
     */
    public function encode()
    {
        return strlen($this->_buffer) . ':' . $this->_buffer;
    }

    /**
     * Reads a stream and decodes the byte character by character. This method
     * will return the remainder of the stream.
     *
     * @param  string $stream The stream to be decoded.
     * @return string         The remainder of the stream, if any.
     */
    public function decode($stream)
    {
        $stream = str_split($stream);
        $buffer = '';
        $size   = count($stream);

        $sizeList = new \SplDoublyLinkedList();
        $bufList  = new \SplDoublyLinkedList();

        // read the size of the byte from the stream
        for ($i = 0; $i < count($stream); $i++) {
            $temp = $stream[$i];
            unset($stream[$i]);
            
            if ($temp === ':') {
                break;
            }

            $sizeList->push($temp);
        }

        $stream = array_values($stream);

        $size = 0;
        
        foreach ($sizeList as $sl) {
            $size .= $sl;
        }

        // read the length of the byte from the stream
        for ($i = 0; $i < $size; $i++) {
            $bufList->push($stream[$i]);
            unset($stream[$i]);
        }

        // read the byte into the buffer
        foreach ($bufList as $bl) {
            $buffer .= $bl;
        }

        $this->_buffer = $buffer;

        return implode('', array_values($stream));
    }

    /**
     * Returns the value stored in the buffer.
     *
     * @return string The raw buffer.
     */
    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Reads a raw byte and stores it in the buffer.
     *
     * @param string $value A raw byte.
     */
    public function read($value)
    {
        $this->_buffer = strval($value);
    }

    /**
     * @return string The encoded Byte
     */
    public function __toString()
    {
        return $this->encode();
    }
}
