<?php

use DSH\Bencode\Integer;

class IntegerTestCase extends PHPUnit_Framework_TestCase
{
	private $_values = array(0, 1, -45, 4000);
	private $_encoded = 'i35e';
	
	private $_improper = array(
		'encoding' => 'g34e', 
		'string' => 'totally wrong'
	);
	
	public function testIntegerConstruction()
	{
		$empty_int = new Integer();
		$valid_int = new Integer($this->_values[1]);
		$encoded_int = new Integer($this->_encoded);
		
		$this->assertEquals('i0e', $empty_int->encode());
		$this->assertEquals('i1e', $valid_int->encode());
		$this->assertEquals($this->_encoded, $encoded_int->encode());
	}
	
	public function testIntegerNegative()
	{
		$negative_int = new Integer($this->_values[2]);
		$this->assertTrue(($negative_int->write() < 0));
	}
	
	public function testIntegerEncoding()
	{
		$encoded_int = new Integer($this->_encoded);
		$this->assertEquals($this->_encoded, $encoded_int->encode());
	}
	
	public function testIntegerValidBuffer()
	{
		$int = new Integer($this->_values[3]);
		$this->assertTrue(is_int($int->write()));
	}
	
	/**
	 * @expectedException \DSH\Bencode\Exceptions\IntegerException
	 */
	public function testIntegerExceptionEncoding()
	{
		$int = new Integer();
		$int->decode($this->_improper['encoding']);
	}
	
	/**
	 * @expectedException \DSH\Bencode\Exceptions\IntegerException
	 */
	public function testIntegerExceptionInvalid()
	{
		$int = new Integer();
		$int->read($this->_improper['string']);
	}
}
