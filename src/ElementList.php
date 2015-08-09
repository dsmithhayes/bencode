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
	
	public function decode($stream, $first = true)
	{
		if($first)
			$stream = substr($stream, 1, -1);
		
		$stream = str_split($stream);
		
		if($stream[0] === 'i') {
			$stream = implode('', $stream);
			$int = new Integer();
			
			$stream = $int->decode($stream);
		}
		elseif(is_numeric($stream[0])) {
			$stream = implode('', $stream);
			$byte = new Byte();
			
			$stream = $byte->decode($stream);
		}
		
		if(strlen($stream) > 0)
			$this->decode($stream, false);
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
