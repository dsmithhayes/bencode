<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\IntegerException;
use DSH\Stack\Stack;

/**
 * An integer is encoded with an 'i' prefix and an 'e' suffix. A
 * good example would be: i45e, i-23e.
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
	 */
	public function __construct($in = 0)
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
	 * Reads a stream character for character until the suffix ('e') is found
	 * and returns the remainder of the string.
	 * 
	 * @param string $stream Reads the stream into the buffer.
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function decode($stream)
	{
		$flag   = false;
		$stream = str_split($stream);
		$stack  = new Stack();
		$buffer = '';
		
		for($i = 0; $i < count($stream); $i++) {
			if($stream[$i] === 'i') {
				$flag = true;
				unset($stream[$i]);
				continue;
			}
			
			if($stream[$i] === 'e') {
				$flag = false;
				unset($stream[$i]);
				break;
			}
			
			if($flag)
				$stack->push($c);
			
			unset($stream[$i]);
		}
		
		foreach($stack->dump() as $d)
			$buffer += $d;
		
		if(!is_numeric($buffer))
			throw new IntegerException('decoded invalid integer');
		
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
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function read($value)
	{
		if(!is_numeric($value))
			throw new IntegerException('reading from non-integer');
			
		$this->_buffer = intval($value);
	}
}
