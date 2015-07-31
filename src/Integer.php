<?php namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Core\Reader;
use Bencode\Exception\IntegerException;

/**
 * The Integer is represented in bencode like: i42e. Each object should represent 
 * one encoded integer.
 */

class Integer extends Reader implements Element, Buffer
{
	/**
	 * Always an integer.
	 */
	private $_buf = 0;
	
	const START = 'i';
	const END = parent::END;
	
	const PATTERN = '/i-?\de/';
	
	/**
	 * Constructing from a non-integer throws an IntegerException from the `read()`
	 * method. Constructing with null leaves the interal buffer as 0.
	 */
	public function __construct($in = null)
	{
		if(isset($in))
			$this->read($in);
	}
	
	/**
	 * Implemented from the Element interface, this method encodes the buffer 
	 * appropriately.
	 */
	public function encode()
	{
		return self::START . $this->_buf . self::END;
	}
	
	/**
	 * Implemented from the Element interface, this method takes in a 
	 * string that represents an encoded integer. This method will throw 
	 * an IntegerException from the `valid()` method if there are any 
	 * errors in the incoming data.  
	 */
	public function decode($in)
	{
		if($this->valid($in))
			$in = $this->dropEncoding($in);
		
		if(!is_int($in))
			throw new IntegerException('Decoding from non-integer.');
		
		$this->_buf = $in;
	}
	
	/**
	 * Throws an IntegerException if its not valid encoded, returns true
	 * if it is.
	 */
	public function valid($in)
	{
		if($this->readFirst($in) !== self::FIRST)
			throw new IntegerException('Improperly encoded: ' . self::START);
		
		if($this->readLast($in) !== self::END)
			throw new IntegerException('Improperly encoded: '. self::END);
		
		return true;		
	}
	
	/**
	 * Implemented from the Buffer interface, this returns the raw data
	 * inside of the object's buffer. 
	 */
	public function write()
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Buffer interface this reads in raw data that
	 * is to be encoded.
	 */
	public function read($in)
	{
		if(!is_int($in))
			throw new IntegerException('Reading in non integer');
		
		$this->_buf = $in;
	}
}
