<?php namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Reader;

use DSH\Bencode\ElementList;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;

use DSH\Bencode\Exceptions\DictionaryException;

/**
 * The dictionary acts as a key-value store of elements.
 */
class Dictionary extends Reader implements Element, Buffer
{
	const START = 'd';
	const END = 'e';
	const PATTERN = '/^d.*e$/';
	
	private $_buf = array();
	
	/**
	 * Construction can only occur from nothing or an existing array that
	 * will act as the key-value store for the Dictionary.
	 * 
	 * @param mixed[] $in Associative array
	 */
	public function __construct($in = array())
	{
		if(empty($in) || is_array($in))
			$this->_buf = $in;
		else
			$this->read($in);
	}
	
	/**
	 * Returns a raw string of an encoded dictionary.
	 * 
	 * @return string Raw encoding of a dictionary element.
	 */
	public function encode()
	{
		$buffer = self::START;
		
		foreach($this->_buf as $key => $value) {
			if(Integer::check($key))
				$key = new Integer($key);
			elseif(Byte::check($key))
				$key = new Byte($key);
			
			if(Integer::check($value))
				$value = new Integer($value);
			elseif(Byte::check($value))
				$value = new Byte($value);
			
			$buffer .= $key->encode();
			$buffer .= $value->encode();
		}
		
		$buffer .= self::END;
		
		return $buffer;
	}
	
	public function decode($in)
	{
		if($this->valid($in))
			$in = $this->dropEncoding($in);
		
	}
	
	public function valid($in)
	{
		if($this->readFirst($in) !== self::START)
			throw new DictionaryException('imporper encoding: ' . $in);
		
		if($this->readLast($in) !== self::END)
			throw new DictionaryException('improper encoding: ' . $in);
		
		return true;
	}
	
	public function check($in)
	{
		
	}
	
	public function write()
	{
		return $this->_buf;
	}
	
	public function read($in)
	{
		
	}
	
	public function length()
	{
		return strlen($this->encode());
	}
	
	/**
	 * 
	 * @param mixed $in the value to be checked if its valid
	 * @throws \DSH\Bencode\Exceptions\ByteException
	 * @throws \DSH\Bencode\Exceptions\IntegerException
	 */
	public function checkElement($in)
	{
		if(Integer::check($in) || Byte::check($in))
			return true;
		
		return false;
	}
}
