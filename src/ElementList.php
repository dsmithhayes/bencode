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
		$buffer = self::START;
		
		foreach($this->_buf as $data)
			$buffer .= $data->encode();
		
		$buffer .= self::END;
		
		return $buffer;
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
		while(isset($in)) {
			
		}
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
	public function readInt(&$in)
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
		for($i = $start; $i <= $end; $i++)
			unset($in[$i]);
		
		// rebuilds the stream sans the integer encoded
		$in = implode("", array_values($in));
		
		foreach($stack->dump() as $d)
			$int .= $d;
		
		return new Integer((int) $int);
	}
	
	/**
	 * Interate through a stream and read an encoded byte.
	 * 
	 * @param string $in a raw stream by reference.
	 * @return \DSH\Bencode\Byte
	 */
	public function readByte(&$in)
	{
		$size = $this->readByteSize($in);
		$raw = $this->readByteRaw($in);
		
		$buffer = array(
			'size' => $size,
			'raw' => $raw
		);
		
		return new Byte($buffer);
	}
	
	/**
	 * Iterate through a stream and match the encoding of a byte.
	 * 
	 * @param string $in The stream to read
	 * @throws \DSH\Bencode\Exceptions\ElementListException
	 * @return int the size of the string
	 */
	public function readByteSize(&$in)
	{
		$flag = false;
		$cursor = 0;
		$stack = new Stack();
		$buffer = 0;
		
		$in = str_split($in);
		
		foreach($in as $c) {
			if($c == Byte::SEPERATOR) {
				$cursor++;
				break;
			}
			
			$stack->push((int) $c);
			$cursor++;
		}
		
		for($i = 0; $i <= $cursor; $i++)
			unset($in[$i]);
		
		$in = implode("", array_values($in));
		
		foreach($stack->dump() as $data)
			$buffer .= $data;
		
		return (int) $buffer;
	}
	
	/**
	 * Iterate through as stream and collect the bytes by the $size.
	 * 
	 * @param string $in a raw stream, by reference
	 * @param int $size how many bytes to read.
	 * @return string the characters read
	 */
	public function readByteRaw(&$in)
	{
		$stack = new Stack();
		$buffer = '';
		
		$size = strlen($in);
		
		$in = str_split($in);
		
		for($i = 0; $i < $size; $i++) {
			$stack->push($in[$i]);
			unset($in[$i]);
		}
		
		$in = implode("", array_values($in));
		
		foreach($stack->dump() as $data)
			$buffer .= $data;
		
		return $buffer;
	}
}
