<?php namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Core\Reader;
use DSH\Bencode\Exceptions\DictionaryException;
use DSH\Bencode\ElementList;

/**
 * The dictionary acts as a key-value store of elements.
 */
class Dictionary extends Reader implements Element, Buffer
{
	const START = 'd';
	const END = 'e';
	
	private $_buf = array();
	
	public function __construct($in = array())
	{
		if(empty($in))
			$this->_buf = $in;
		elseif(is_array($in))
			foreach($in as $key => $value)
				if($this->checkElement($key) && $this->checkElement($value))
					$this->_buf[$key] = $value;
		else
			$this->read($in);
	}
	
	public function encode()
	{
		$buffer = self::START;
		
		foreach($this->_buf as $key => $value) {
				
		}
		
		$buffer .= self::END;
		
		return $buffer;
	}
	
	public function decode($in)
	{
		
	}
	
	public function valid($in)
	{
		if($this->readFirst($in) !== self::START)
			throw new DictionaryException('imporper encoding: ' . $in);
		
		if($this->readLast($in) !== self::END)
			throw new DictionaryException('improper encoding: ' . $in);
		
		return true;
	}
	
	public function write()
	{
		
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
		if(ElementList::checkInteger($in) || ElementList::checkByte($in))
			return true;
		
		return false;
	}
}
