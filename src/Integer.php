<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Exceptions\IntegerException;
use DSH\Stack\Stack;

class Integer implements Element, Buffer
{
	protected $_buffer;
	
	/**
	 * @param int $in An integer value to be represented. 
	 */
	public function __construct($in = 0)
	{
		if(!is_numeric($in))
			throw new IntegerException('construction from non-integer');
		
		$this->_buffer = $in;
	}
	
	public function encode()
	{
		return 'i' . $this->_buffer . 'e';
	}
	
	public function decode($stream)
	{
		$flag = false;
		$stream = str_split($stream);
		$stack = new Stack();
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
	
	public function read($value)
	{	
		$this->_buffer = intval($value);
	}
	
	public function write()
	{
		return $this->_buffer;
	}
}
