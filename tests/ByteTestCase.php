<?php

use DSH\Bencode\Byte;
use DSH\Bencode\Integer;

class ByteTestCase extends PHPUnit_Framework_TestCase
{
	private $_array = array('size' => 4, 'raw' => 'test');
	private $_string = 'test';
	private $_encoded = '4:test';
	
	public function testConstructorException()
	{
		$empty_byte = new Byte();
		$array_bte = new Byte($this->_array);
		$string_byte = new Byte($this->_string);
		$encoded_byte = new Byte($this->_encoded);
		$obj_byte = new Byte($empty_byte);
	}
}
