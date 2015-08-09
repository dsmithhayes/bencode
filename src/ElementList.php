<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Buffer;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Exceptions\ElementListException;
use DSH\Stack\Stack;

/**
 * The element list is a stream of encoded elements in a sequence.
 */
class ElementList implements Element, Buffer
{
	const PATTERN = '/^l.*e$/';
	
	/**
	 * @var mixed[] An array of raw values that make up the list.
	 */
	protected $_buffer;
	
	public function __construct($buffer = array())
	{
		$this->read($buffer);
	}
	
	public function encode()
	{
		$buffer = '';
		
		foreach($this->_buffer as $b) {
			if(is_numeric($b)) {
				$int = new Integer($b);
				$buffer .= $int->encode();
			}
			else {
				$byte = new Byte($b);
				$buffer .= $byte->encode();
			}
		}
		
		return 'l' . $buffer . 'e';
	}
	
	public function decode($stream)
	{
		if(!preg_match(self::PATTERN, $stream))
			throw new ElementListException('invalid encoding: ' . $stream);
		
		$stream = str_split($stream);
		$size = count($stream);
		
		unset($stream[0]);
		unset($stream[$size - 1]);
		
		$stream = array_values($stream);
		$size = count($stream);
		
	}
	
	public function write()
	{
		return $this->_buffer;
	}
	
	public function read($value)
	{
		if(is_array($value))
			$this->_buffer = $value;
		else
			$this->_buffer[] = $value;
	}
}
