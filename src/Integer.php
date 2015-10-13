<?php

namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Exception\IntegerException;

/**
 * An integer is encoded with an 'i' prefix and an 'e' suffix. A good example
 * would be: i45e, i-23e.
 */
class Integer implements Element, Buffer
{
    /**
     * @const PATTERN The regex pattern that matches an encoded integer.
     */
    const PATTERN = '/^i\d+e/';

    /**
     * @var int A raw integer buffer.
     */
    protected $_buffer;

    /**
     * @param int $in An integer value to be represented.
     *
     * @throws Bencode\Exception\IntegerException
     */
    public function __construct($in = null)
    {
        $this->read($in);
    }

    /**
     * Returns the encoded buffer.
     *
     * @return string A stream of the encoded integer.
     */
    public function encode()
    {
        return 'i' . $this->_buffer . 'e';
    }

    /**
     * @todo make recursive
     * 
     * Reads a stream character for character until the suffix ('e') is found
     * and returns the remainder of the string.
     *
     * @param string $stream Reads the stream into the buffer.
     *
     * @throws Bencode\Exceptions\IntegerException
     */
    public function decode($stream)
    {
        $flag   = false;
        $stream = str_split($stream);
        $buffer = '';
        $size   = count($stream);

        $stack  = new \SplDoublyLinkedList();

        for($i = 0; $i < ($size - 1); $i++) {
            if ($flag) {
                if(is_numeric($stream[$i]) || $stream[$i] === '-') {
                    $stack->push($stream[$i]);
                }
            }

            if ($stream[$i] === 'i') {
                $flag = true;
            }

            if ($stream[$i] === 'e') {
                $flag = false;
                unset($stream[$i]);
                break;
            }

            unset($stream[$i]);
        }

        foreach ($stack as $s) {
            $buffer .= $s;
        }

        $this->_buffer = $buffer;

        return implode('', array_values($stream));
    }

    /**
     * Returns the raw buffer.
     *
     * @return int The value in the buffer.
     */
    public function write()
    {
        return $this->_buffer;
    }

    /**
     * Reads in a raw value and stores it in the buffer.
     *
     * @param int $value An integer to store in the buffer.
     *
     * @throws IntegerException
     */
    public function read($value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        
        if (!is_numeric($value)) {
            throw new IntegerException('Reading from non-integer: ' . $value);
        }

        $this->_buffer = intval($value);
    }

    /**
     * Hard choice, __toString() the encoded Integer or the raw buffer? I
     * decided the encoded Integer.
     *
     * @return string The encoded integer.
     */
    public function __toString()
    {
        return $this->encode();
    }
}
