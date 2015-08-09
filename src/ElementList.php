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
		$stream    = str_split($stream);
		$byte_flag = false;
		$int_flag  = false;
		
		for($i = 0; $i < count($stream); $i++) {
			if($stream[$i] === 'l') {
				unset($stream[$i]);
				continue;
			}
			
			if($int_flag) {
				$stream = implode('', array_values($stream));
				$int = new Integer();
				$stream = $int->decode($stream);
				$this->_buffer[] = $int->write();
				$stream = str_split($stream);
				$int_flag = false;
				continue;
			}
			
			if($byte_flag) {
				$stream = implode('', array_values($stream));
				$byte = new Byte();
				$stream = $byte->decode($stream);
				$this->_buffer[] = $byte->write();
				$stream = str_split($stream);
				$byte_flag = false;
				continue;
			}
			
			if(is_numeric($stream[$i])) {
				$byte_flag = true;
				$i--;
				continue;
			}
			
			if($stream[$i] === 'i') {
				$int_flag = true;
				$i--;
				continue;
			}
		}
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
	
	protected function _readInt($value)
	{
		return (new Integer($value));
	}
	
	protected function _readByte($value)
	{
		return (new Byte($value));
	}
}
