<?php namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Core\Reader;
use Bencode\Exception\ByteException;

/**
 * Bytes are encoded with the size of the value and the value itself
 * seperated by a colon. So like this: 4:test
 */

class Byte implements Element, Buffer
{
	/**
	 * Always an associative array
	 */
	private $_buf = [
		'size' => 0,
		'raw'  => '',
	];

	const SEPERATOR = ':';
	const PATTERN = '/[0-9]\:[\D]/';
	
	/**
	 * Construction can be done in three ways. 
	 * 
	 * 1. As an internal buffer
	 * 2. as an encoded Byte
	 * 3. default (empty)
	 */
	public function __construct($in = null)
	{
		if(isset($in))
			if(is_array($in))
				$this->_buf = $in;
			else
				$this->read($in);
	}
	
	/**
	 * Implemented from the Core\Element interface this returns a stream
	 * of an encoded byte. Something like '4:test'
	 */
	public function encode()
	{
		if($this->_buf['size'] !== strlen($this->_buf['raw']))
			throw new ByteException('Internal Buffer: size mismatch.');
		
		return $this->_buf['size'] . self::SEPERATOR . $this->_buf['raw'];
	}
	
	/**
	 * Implemented from the Core\Element interfae this takes in a formatted
	 * stream of a byte and places it in the internal buffer. 
	 */
	public function decode($in)
	{
		// throws ByteException here.
		if($this->valid($in))
			$this->read(explode(":", $in)[1]);
	}
	
	/**
	 * Throws an IntegerException if the encoding isn't proper.
	 */
	public function valid($in)
	{
		if(!preg_match(self::PATTERN, $in))
			throw new ByteException('Improper byte encoding: ' $in);
	}
	
	/**
	 * Implemented from the Core\Buffer interface, it returns the internal
	 * buffer of the object.
	 */
	public function write() 
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Core\Reader interface, it reads a raw strea and
	 * places it in the interal buffer.
	 */
	public function read($in) 
	{
		$this->_buf['size'] = strlen($in);
		$this->_buf['raw'] = $in;	
	} 
}
