<?php namespace DSH\Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Core\Reader;
use Bencode\Exceptions\ElementListException;

use DSH\Stack\Stack;

/**
 * Represents a list element in bencode.
 */
class ElementList extends Reader implements Element, Buffer
{
	const START = 'l';
	const END = 'e';
	
	private $_buf;
	private $_stack;
	
	/**
	 * Construct from nothing or an array of elements.
	 * 
	 * @param mixed[]|null $in If set, create a list of the elements 
	 */
	public function __construct($in = array())
	{
		if(empty($in))
			$this->_buf = $in;
		else {
			foreach($in as $i) {
				if($this->_checkInteger($i))
				
			}
		}
	}
	
	public function encode()
	{
		
	}
	
	public function decode($in)
	{
		
	}
	
	public function valid($in)
	{
		
	}
	
	public function read($in)
	{
		
	}
	
	public function write()
	{
		
	}
	
	/**
	 * Checks if the value is either a raw integer or an Integer object.
	 * 
	 * @param int|\DSH\Bencode\Integer $in the value in question
	 * @return bool Returns true if valid integer.
	 */
	private function _checkInteger($in)
	{
		if(is_int($in))
			return true;
		
		if($in instanceof Integer)
			return true;
		
		return false;
	}
	
	/**
	 * Checks if the value is a raw byte stream, an encoded byte stream or a
	 * Byte element object.
	 * 
	 * @param string|\DSH\Bencode\Byte $in the byte in question
	 * @return \DSH\Bencode\Byte Returns an instance of a Byte object
	 */
	private function _checkByte($in)
	{
		if(Byte::valid($in))
			return true;
		
		if($in instanceof Byte)
			return true;
		
		return false;
	}
}
