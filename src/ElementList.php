<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\ElementListException;

class ElementList implements Element, Buffer
{
	const PATTERN = '/^l.*e$/';
	
	protected $_buffer;
	
	public function __construction($buffer = array())
	{
		
	}
	
	public function encode()
	{
		
	}
	
	public function decode($stream)
	{
		
	}
	
	public function write()
	{
		
	}
	
	public function read($value)
	{
		
	}
}
