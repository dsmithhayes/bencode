<?php namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Reader;
use DSH\Bencode\Exceptions\ByteException;

/**
 * Bytes are encoded with the size of the value and the value itself
 * seperated by a colon. So like this: 4:test. Byte elements don't extend
 * the Reader class because they are encoded differently than the other
 * elements.
 * 
 * @author Dave Smith-Hayes <dsmithhayes@gmail.com>
 */
class Byte implements Element, Buffer
{
	const SEPERATOR = ':';
	const PATTERN = '/\d+:\w+/';
	
	private $_buf = array(
		'size' => 0,
		'raw'  => ''
	);
	
	/**
	 * You can construct an empty byte, or give it a stream to encode. The
	 * value can also be an encoded string.
	 * 
	 * @param string|null if set, create the byte from the string
	 * @throws \DSH\Bencode\Exceptions\ByteException
	 */
	public function __construct($in = null)
	{
		if(isset($in))
			if(is_string($in))
				if(preg_match(self::PATTERN, $in))
					$this->decode($in);
				else
					$this->read($in);
			elseif(is_array($in)) {
				if(array_key_exists('size', $in)
						&& array_key_exists('raw', $in))
				{
					$this->_buf['size'] = $in['size'];
					$this->_buf['raw'] = $in['raw'];
				}
				else
					throw new ByteException('improper buffer array');
			}
			elseif($in instanceof self)
				$this->_buf = $in->write();
			else
				throw new ByteException('invalid byte');
	}
	
	/**
	 * Implemented from the Core\Element interface this returns a stream
	 * of an encoded byte. Something like '4:test'
	 * 
	 * @throws \DSH\Bencode\Exceptions\ByteException
	 * @return string Encoded byte stream
	 */
	public function encode()
	{
		if($this->_buf['size'] !== strlen($this->_buf['raw']))
			throw new ByteException('internal buffer: size mismatch');
		
		return $this->_buf['size'] . self::SEPERATOR . $this->_buf['raw'];
	}
	
	/**
	 * Implemented from the Core\Element interfae this takes in a formatted
	 * stream of a byte and places it in the internal buffer.
	 * 
	 * @param string $in An encoded byte (4:test)
	 * @throws \DSH\Bencode\Exceptions\ByteException
	 * @return void
	 * 
	 */
	public function decode($in)
	{
		// throws ByteException here.
		if($this->valid($in)) {
			$in = explode(":", $in);
			$this->read($in[1]);
		}
	}
	
	/**
	 * Checks if the stream is valid.
	 * 
	 * @param string $in An encoded byte (4:test)
	 * @throws \DSH\Bencode\Exceptions\ByteException
	 * @return bool Returns true if the byte is encoded properly.
	 */
	public function valid($in)
	{		
		if(!preg_match(self::PATTERN, $in))
			throw new ByteException('improper byte encoding: ' . $in);
		
		$in = explode(':', $in);
		
		if((int) $in[0] !== strlen($in[1]))
			throw new ByteException('internal buffer: size mismatch');
		
		return true; 
	}
	
	/**
	 * Implemented from the Core\Buffer interface, it returns the internal
	 * buffer of the object.
	 * 
	 * @return mixed[] The internal buffer
	 */
	public function write() 
	{
		return $this->_buf;
	}
	
	/**
	 * Implemented from the Core\Buffer interface, it reads a raw stream
	 * and converts it into the internal buffer array.
	 * 
	 * @param string $in The raw stream
	 */
	public function read($in) 
	{
		$this->_buf['size'] = strlen($in);
		$this->_buf['raw'] = $in;	
	}
	
	/**
	 * Implemented from the Buffer interface, returns the length of the raw
	 * data stream
	 * 
	 * @return int the length of the raw data stream
	 */
	public function length()
	{
		return strlen($this->encode());
	}
}
