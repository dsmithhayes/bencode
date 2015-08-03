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
	
	/**
	 * @var mixed[] $_buf the internal buffer of the list.
	 */
	private $_buf = array();
	
	/**
	 * Construct from nothing or an array of elements.
	 * 
	 * @param mixed[]|null $in If set, create a list of the elements
	 */
	public function __construct($in = array())
	{
		if(empty($in))
			$this->_buf = $in;
		elseif(is_array($in))
			foreach($in as $i)
				if($this->checkInteger($i))
					$this->_buf[] = new Integer($i);
				elseif($this->checkByte($i))
					$this->_buf[] = new Byte($i);
		else
			$this->read($in);
	}
	
	/**
	 * Encodes the internal buffer as a raw stream.
	 * 
	 * @return string the raw encoded stream of a list
	 */
	public function encode()
	{
		$buffer = self::START;
		
		foreach($this->_buf as $data)
			$buffer .= $data->encode();
		
		$buffer .= self::END;
		
		return $buffer;
	}
	
	/**
	 * Decodes an encoded steam of the element list.
	 * 
	 * @param string $in the raw stream of an element list
	 * @throws \DSH\Bencode\Exceptions\ElementListException
	 */
	public function decode($in)
	{
		if($this->valid($in)) {		// ElementListException thrown here
			$in = $this->dropEncoding($in);
			$this->read($in);
		}
	}
	
	/**
	 * Checks the validity of the stream.
	 * 
	 * @param string $in the encoded stream of an element list.
	 * @throws \DSH\Bencode\Exceptions\ElementListException
	 * @return bool returns true if the stream is encoded properly.
	 */
	public function valid($in)
	{
		if($this->readFirst($in) != self::START)
			throw new ElementListException('improper encoding: ' . $in);
		
		if($this->readLast($in) != self::END)
			throw new ElementListException('improper encoding: '. $in);
		
		return true;
	}
	
	/**
	 * Evaluates each part of the list, places the object of the element
	 * into the internal value.
	 * 
	 * @param string $in the encoded stream, sans the first and last character
	 */
	public function read($in)
	{
		$int_flag = false;
		$byte_flag = false;
		$buffer = null;
		
		$temp = str_split($in);
		
		for($i = 0; $i < count($temp); $i++) {
			
			// read an integer from the list
			if($int_flag) {
				$buffer = $this->readInt($in);
				$this->_buf[] = $buffer;
				
				$i += ($buffer->length() - 1);
				
				$int_flag = false;
				continue;
			}
			
			// read a byte from the list
			if($byte_flag) {
				$buffer = $this->readByte($in);
				$this->_buf[] = $buffer;
				
				$i += ($buffer->length() - 1);
				
				$byte_flag = false;
				continue;
			}
			
			// check if its a byte or integer in the list.
			if($temp[$i] == Integer::START) {
				$int_flag = true;
				$i--;
				continue;
			}
			elseif(is_numeric($temp[$i])) {
				$byte_flag = true;
				$i--;
				continue;
			}
		}
	}
	
	/**
	 * Implemented from the Buffer interface this returns the raw data buffer
	 * of the element.
	 * 
	 * @return mixed[] internal list element buffer
	 */
	public function write()
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Buffer interface, returns the length of the raw
	 * stream data.
	 * 
	 * @return int length of the raw stream
	 */
	public function length()
	{
		return strlen($this->encode());
	}
	
	/**
	 * Checks if the value is either a raw integer or an Integer object.
	 * 
	 * @param int|\DSH\Bencode\Integer $in the value in question
	 * @return bool Returns true if valid integer.
	 */
	public function checkInteger($in)
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
	public function checkByte($in)
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
		$int = '';
		
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
				
				$stack->push($c);
				$cursor++;
				continue;
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
		$raw = $this->readByteRaw($in);
		
		$buffer = array(
			'size' => strlen($raw),
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
		$buffer = '';
		
		$in = str_split($in);
		
		foreach($in as $c) {
			if($c == Byte::SEPERATOR) {
				$cursor++;
				break;
			}
			
			$stack->push($c);
			$cursor++;
		}
		
		for($i = 0; $i < $cursor; $i++)
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
		
		$size = $this->readByteSize($in);
		
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
