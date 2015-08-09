<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\ByteException;
use DSH\Stack\Stack;

class Byte implements Element, Buffer
{
	const PATTERN = '/\d+:\w+';
	
	protected $_buffer;
	
	/**
	 * @param string $buffer a raw byte.
	 */
	public function __construct($buffer = '')
	{
		$this->read($buffer);
	}
	
	public function encode()
	{
		return strlen($this->_buffer) . ':' . $this->_buffer; 
	}
	
	public function decode($stream)
	{
		$stream = str_split($stream);
		$buffer = '';
		$size   = 0;
		
		foreach($stream as $c) {
			if(is_numeric($c)) {
				
			}
		}
	}
	
	public function write()
	{
		return $this->_buffer;
	}
	
	public function read($value)
	{
		$this->_buffer = $value;
	}
}
