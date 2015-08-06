<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\IntegerException;
use DSH\Stack\Stack;

/**
 * An integer is encoded with an 'i' prefix and an 'e' suffix. A
 * good example would be: 45, -23 
 */
class Integer implements Element, Buffer
{
	protected $_buffer;
	
	/**
	 * @param int $in An integer value to be represented. 
	 */
	public function __construct($in = 0)
	{
		$this->read($in);
	}
	
	/**
	 * @return string A stream of the encoded integer.
	 */
	public function encode()
	{
		return 'i' . $this->_buffer . 'e';
	}
	
	/**
	 * @param string $stream Reads the stream into the buffer.
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function decode($stream)
	{
		$flag   = false;
		$stream = str_split($stream);
		$stack  = new Stack();
		$buffer = '';
		
		foreach($stream as $c) {
			if($c === 'i') {
				$flag = true;
				continue;
			}
			
			if($c === 'e') {
				$flag = false;
				continue;
			}
			
			if($flag)
				$stack->push($c);
		}
		
		foreach($stack->dump() as $i)
			$buffer .= $i;
		
		if(!is_numeric($buffer))
			throw new IntegerException('decoded invalid integer');
		
		$this->_buffer = $buffer;
	}
	
	/**
	 * @return int The value in the buffer.
	 */
	public function write()
	{
		return $this->_buffer;
	}
	
	/**
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
