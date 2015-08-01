<?php namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Core\Reader;
use Bencode\Core\Stack;
use Bencode\Exception\ElementListException;

/**
 * Because of PHP's keyword 'list' this object can't actually be called
 * 'List' so instead it gets a bit longer of a name.
 * 
 * The ElementList is a raw list of ints and bytes starting with an 'l'
 * and ending with an 'e'.
 */

class ElementList extends Reader implements Element, Buffer
{
	/**
	 * Always an indexed array of Bytes and Integers
	 */
	private $_buf = [];
	
	const START = 'l';
	const END = parent::END;
	
	/**
	 * Gnarly REGEX bro, it tears apart an encoded list.
	 */
	const PATTERN = '(i-?\d+e)|(\d+\:\D+)';
	
	/**
	 * Six ways to instantiate an ElementList object. 
	 * 
	 * 1. null - empty list
	 * 2. Integer - with an Integer object
	 * 3. Byte - with a Byte object
	 * 4. Array - as an array of Byte and Integer objects
	 * 4. Array - as an array of raw data to be encoded.
	 * 5. Encoded - Any encoded ElementList representation.
	 */
	public function __construct($in = null)
	{
		if(isset($in))
			if($in instanceof Integer || $in instanceof Byte)
				$this->_buf[] = $in;
			elseif(is_array($in))
				foreach($in as $element)
					if($element instanceof Integer || $element instanceof Byte)
						$this->_buf[] = $element;
					elseif(is_int($element))
						$this->_buf[] = new Integer($element);
					else
						$this->_buf[] = new Byte($element);
			else
				$this->read($in);
	}
	
	/**
	 * Implemented from the Element interface, returns an encoded
	 * ElementList 
	 */
	public function encode()
	{
		$temp = self::START;
		
		foreach($this->_buf as $element)
			$temp .= $element->encode();
		
		return $temp .= self::END
	}
	
	/**
	 * Implemented from the Element interface, takes in an encoded
	 * ElementList and places it into the buffer.
	 */
	public function decode($in)
	{
		if($this->valid($in))
			$in = $this->dropEncoding($in);
		
		$this->read($in);
	}
	
	/**
	 * Throws an ElementListException if the encoding is not valid
	 */
	public function valid($in)
	{
		if($this->readFirst($in) !== self::START)
			throw new ElementListException('Improperly encoded: ' . self::START);
		
		if($this->readLast($in) !== self::END)
			throw new ElementListException('Improperly encoded: '. self::END);
		
		return true;
	}
	
	/**
	 * Returns the internal buffer
	 */
	public function write()
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Buffer interface, sets the internal 
	 * buffered list from an encoded string. This will throw the
	 * ElementListException.
	 */
	public function read($in)
	{
		$int_flag = false;
		$byte_flag = false;		// read a byte
		$byte_switch = false;	// false = read size; true = read chars
		$byte_size = 0;			// the size of the byte
		
		// throws ElementListException here
		if($this->valid($in))
			$in = $this->dropEncoding($in);
		
		$buffer = new str_split($in);
		$int_stack = new Stack();
		$byte_stack = new Stack();
		
		// step through the stream
		foreach($buffer as $buf) {
			
			// decode integers from the list 
			if($int_flag) {
				if($buf !== parent::END)
					$int_stack->push($buf);
				else {
					foreach($int_stack->dump() as $int)
						$in .= $int;
					
					$this->_buf[] = new Integer($in);
					$int_flag = false;
				}
				
				continue;
			}
			
			// decode the byte
			elseif($byte_flag) {
				
				// read the byte
				if($byte_switch) {
					
				}
				else {
					// read the size
					if($buf !== Byte::SEPERATOR)
						$byte_stack->push($buf);
					
					
				}
				
				continue;
			}
			
			if($buf === Integer::START)
				$byte_flag = true;
			elseif(is_int($buf))
				$int_flag = true;
		}
	}
}
