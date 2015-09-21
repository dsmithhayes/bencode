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
        
        foreach ($this->_buffer as $k => $v) {
            $key = new Byte();
            
            $key->read($k);
            
            if (is_array($v)) {
                $dictionary = false;
                
                foreach (array_keys($v) as $ak) {
                    if (!is_numeric($ak)) {
                        $dictionary = true;
                    }
                }
                
                if ($dictionary) {
                    $value = new Dictionary();
                } else {
                    $value = new BList();
                }
            } else {
                $value = new Byte();
            }
            
            $value->read($v);
            
            $buffer .= ($key->encode() . $value->encode());
        }

        $buffer .= 'e';

        return $buffer;
    }

    /**
     * Decoding a Dictionary is a lot like decoding a List, however we are
     * looking for the firs two Elements and setting them as the key and value
     * of the array.
     * 
     * A key must be an encoded byte, however the value can be any of the other
     * elements, such as an Integer, List or another Dictionary.
     * 
     * @param string $stream Encoded Dictionary
     * 
     * @return string the remainder of the stream, if anything
     */
    public function decode($stream)
    {
        $stream = $this->dropEncoding($stream, self::PATTERN);
        
        if (!is_numeric($stream[0])) {
            throw new DictionaryException(
                'Invalid dictionary encoding: ' . $stream
            );
        }
        
        $key = new Byte();
        
        // always read the key first, then onto the value
        $stream = $key->decode($stream);
        
        if (is_numeric($stream[0])) {
            $value = new Byte();
        } elseif ($stream[0] === 'l') {
            $value = new BList();
        } elseif ($stream[0] === 'd') {
            $value = new Dictionary();
        } else {
            throw new DictionaryException(
                'Improper dictionary encoding: ' . $stream
            );
        }
        
        $stream = $value->decode($stream);
        
        // assign the key and value.
        $this->_buffer[$key->write()] = $value->write();
        
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
     * Reading will place the array into the internal buffer. Indexed arrays
     * will have their indices encoded as Bytes.
     *
     * @param array $value An array to become the Dictionary
     *
     * @throws DictionaryException
     */
    public function read($value)
    {
        if (is_null($value)) {
            $value = [];
        }
        
        if (!is_array($value)) {
            throw new DictionaryException('Reading to buffer from non-array');
        }
        
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
