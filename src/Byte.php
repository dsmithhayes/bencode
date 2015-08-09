<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\ByteException;
use DSH\Stack\Stack;

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
		$size   = 0;
		
		$size_stack   = new Stack();
		$buffer_stack = new Stack();
		
		for($i = 0; $i < count($stream); $i++) {
			if($stream[$i] === ':') {
				unset($stream[$i]);
				break;
			}
			
			$size_stack->push($stream[$i]);
			unset($stream[$i]);
		}
		
		$stream = array_values($stream);
		
		foreach($size_stack->dump() as $d)
			$size += $d;
		
		for($i = 0; $i < $size; $i++) {
			$buffer_stack->push($stream[$i]);
			unset($stream[$i]);
		}
		
		foreach($buffer_stack->dump() as $c)
			$buffer .= $c;
		
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
	 * @param stream $value A raw byte.
	 */
	public function read($value)
	{
		$this->_buffer = $value;
	}
}
