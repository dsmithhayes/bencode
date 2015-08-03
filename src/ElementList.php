<?php namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Reader;
use DHS\Bencode\Exceptions\ElementListException;

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
		else
			foreach($in as &$i)
				if($this->_checkInteger($i))
					$this->_buf[] = new Integer($i);
				elseif($this->_checkByte($i))
					$this->_buf[] = new Byte($i);
	}
	
	public function encode()
	{
		
	}
	
	public function decode($in)
	{
		
	}
	
	public function valid($in)
	{
		if($this->readFirst($in) != self::START)
			throw new ElementListException('improper encoding: ' . $in);
		
		if($this->readLast($in) != self::END)
			throw new ElementListException('improper encoding: '. $in);
		
		return true;
	}
	
	public function read($in)
	{
		
	}
	
	public function write()
	{
		return $this->_buf;
	}
	
	/**
	 * Checks if the value is either a raw integer or an Integer object.
	 * 
	 * @param int|\DSH\Bencode\Integer $in the value in question
	 * @return bool Returns true if valid integer.
	 */
	private function _checkInteger($in)
	{
		if($in instanceof Integer)
			return true;
		
		if(preg_match(Integer::PATTERN, $in))
			return true;
		
		if(is_int($in))
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
		if($in instanceof Byte)
			return true;
		
		if(preg_match(Byte::PATTERN, $in))
			return true;
		
		if(is_string($in))
			return true;
		
		return false;
	}
	
	/**
	 * Iterate through a stream and match the encoding of an integer.
	 * 
	 * @param string $in The stream to read as an array
	 * @return \DSH\Bencode\Integer
	 */
	private function _readInt(&$in)
	{
		$flag = false;
		$stack = new Stack();
		$int = 0;
		
		$cursor = 0;
		$start = 0;
		$end = 0;
		
		$in = str_split($in);
		
		// steps through each character of the stream
		foreach($in as $c) {
			if($flag) {
				if($c == Integer::END) {
					$flag = false;
					$end = $cursor;
					break;
				}
				
				$stack->push((int) $c);
				$cursor++;
			}
			
			if($c == Integer::START) {
				$flag = true;
				$start = $cursor++;
			}
		}
		
		// remove the int from the list
		for($i = $start; $i < $end; $i++)
			unset($in[$i]);
		
		// rebuilds the stream sans the integer encoded
		$in = implode("", array_values($in));
		
		foreach($stack->dump() as $d)
			$int += (int) $d;
		
		return new Integer($int);
	}
	
	/**
	 * Iterate through a stream and match the encoding of a byte.
	 * 
	 * @param string $in The stream to read
	 * @throws \DSH\Bencode\Exceptions\ElementListException
	 * @return \DSH\Bencode\Byte
	 */
	private function _readByte(&$in)
	{
		$size_flag = false;
		$raw_flag = false;
		
		$byte_stack = new Stack();
		$int_stack = new Stack();
		
		$byte_buffer = array('size' => 0, 'raw' => '');
		
		$start = 0;
		$end = 0;
		$cursor = 0;
		
		$in = str_split($in);
		
		// steps through the stream
		foreach($in as $c) {
			if($size_flag) {
				if(is_int($c)) {
					$int_stack->push((int) $c);
					$cursor++;
					continue;
				}
			}
			
			if($raw_flag) {
				$byte_stack->push($c);
				$cursor++;
			}
			
			if(is_int($c)) {
				$size_flag = true;
				$start = $cursor++;
				$int_stack->push($c);
				continue;
			}
			
			if($c == Byte::SEPERATOR) {
				$size_flag = false;
				
				foreach($int_stack->dump() as $i)
					$byte_buffer['size'] += (int) $i;
				
				$raw_flag = true;
				$cursor++;
				continue;
			}
		}
		
		for($i = $start; $i < $end; $i++)
			unset($in[$i]);
		
		$in = implode("", array_values($in));
		
		return new Byte($byte);
	}
}
