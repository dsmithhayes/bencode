<?php namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Reader;
use DSH\Bencode\Exceptions\IntegerException;

/**
 * The Integer is represented in bencode like: i42e. Each object should represent 
 * one encoded integer.
 */

class Integer extends Reader implements Element, Buffer
{
	const START = 'i';
	const END = 'e';
	
	const PATTERN = '/i-?\d+e/';

	private $_buf = 0;
	
	/**
	 * Constructing from a non-integer throws an IntegerException from the 
	 * `read()` method. Constructing with null leaves the interal buffer as 0.
	 * 
	 * @param int|null $in The value to encode
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function __construct($in = null)
	{
		if(isset($in))
			if($in instanceof self)
				$this->read($in->write());
			elseif(is_int($in))
				$this->read($in);
			elseif($this->valid($in))
				$this->decode($in);
	}
	
	/**
	 * Implemented from the Element interface, this method encodes the buffer 
	 * appropriately.
	 * 
	 * @return string Encoded stream of the integer
	 */
	public function encode()
	{
		return self::START . $this->_buf . self::END;
	}
	
	/**
	 * Implemented from the Element interface, this method takes in a 
	 * string that represents an encoded integer.
	 * 
	 * @param string An encoded integer value
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function decode($in)
	{
		if($this->valid($in))
			$in = (int) $this->dropEncoding($in);
		
		if(!is_int($in))
			throw new IntegerException('decoding from non-integer');
		
		$this->_buf = $in;
	}
	
	/**
	 * Throws an IntegerException if its not valid encoded, returns true
	 * if it is.
	 * 
	 * @param string $in Encoded integer value
	 * @throws \DSH\Bencod\Exceptions\IntegerException
	 * @return bool Returns true if the value is encoded properly.
	 */
	public function valid($in)
	{
		if($this->readFirst($in) != self::START)
			throw new IntegerException('improperly encoded: ' . $in);
		
		if($this->readLast($in) != self::END)
			throw new IntegerException('improperly encoded: '. $in);
		
		return true;
	}
	
	/**
	 * Implemented from the Element interface, returns true if the given
	 * value can be represented as an Integer.
	 * 
	 * @param \DSH\Bencode\Integer|int|string $in an Integer instance, an
	 * 	actual integer or an encoded stream of an integer.
	 * @return bool Returns true if the value can be an Integer. 
	 */
	public function check($in)
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
	 * Implemented from the Buffer interface, this returns the raw data
	 * inside of the object's buffer.
	 * 
	 * @return int The integer in the buffer
	 */
	public function write()
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Buffer interface this reads in raw data that
	 * is to be encoded.
	 * 
	 * @param int $in The integer to read into the buffer
	 */
	public function read($in)
	{
		if(!is_int($in))
			throw new IntegerException('reading from non integer');
		
		$this->_buf = $in;
	}
	
	/**
	 * Implemented from the Buffer interface, this returns the total length
	 * of the raw stream of data.
	 * 
	 * @return int the length of the raw stream
	 */
	public function length()
	{
		return strlen($this->encode());
	}
}
