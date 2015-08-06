<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;

class Integer implements Element, Buffer
{
	protected $_buffer;
	
	/**
	 * @param int $in An integer value to be represented. 
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function __construct($in = 0)
	{
		if(!$this->_check($in))
			throw new IntegerException('construction from non-integer');
		
		$this->_buffer = $in;
	}
	
	public function encode()
	{
		
	}
	
	public function decode($stream)
	{
		
	}
	
	public function read($value)
	{
		
	}
	
	public function write()
	{
		
	}
	
	/**
	 * Checks if the value given is a valid integer.
	 * 
	 * @param int|string $value An integer
	 * @return bool Returns true if a valid integer.
	 */
	protected function _check($value)
	{
		if(!ctype_digit(strval($value)))
			return false;
		
		return true;
	}
}
