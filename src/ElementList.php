<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\ElementListException;
use DSH\Stack\Stack;

class ElementList implements Element, Buffer
{
	const PATTERN = '/^l.*e$/';
	
	protected $_buffer;
	
	public function __construct($buffer = array())
	{
		$this->read($buffer);
	}
	
	public function encode()
	{
		foreach($this->_buffer as $b)
			$buffer .= $b;
		
		return 'l' . $buffer . 'e';
	}
	
	public function decode($stream)
	{
		
	}
	
	public function write()
	{
		return $this->_buffer;
	}
	
	public function read($value)
	{
		if(is_array($value))
			$this->_buffer = $value;
		else
			$this->_buffer[] = $value;
	}
}
