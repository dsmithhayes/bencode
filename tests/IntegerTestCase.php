<?php

use DSH\Bencode\Integer;

class IntegerTestCase extends PHPUnit_Framework_TestCase
{
	public function testEmptyConstruction()
	{
		$integer = new Integer();
		$this->assertEquals(0, $integer->write());
	}
	
	public function testBasicConstruction()
	{
		$integer = new Integer(1);
		$this->assertEquals(1, $integer->write());
		
		return $integer;
	}
	
	public function testNegativeConstruction()
	{
		$integer = new Integer(-1);
		$this->assertEquals(-1, $integer->write());
		
		return $integer;
	}
	
	public function testStringConstruction()
	{
		$integer = new Integer('1');
		$this->assertEquals(1, $integer->write());
	}
	
	public function testNegativeStringConstruction()
	{
		$integer = new Integer('-1');
		$this->assertEquals(-1, $integer->write());
	}
	
	public function testFloatConstruction()
	{
		$integer = new Integer(1.5);
		$this->assertEquals(1, $integer->write());
	}
	
	/**
	 * @expectedException \DSH\Bencode\Exceptions\IntegerException
	 */
	public function testInvalidConstruction()
	{
		$integer_string = new Integer('words');
	}
	
	/**
	 * @depends testBasicConstruction
	 */
	public function testEncoding(Integer $int)
	{
		$this->assertEquals('i1e', $int->encode());
	}
	
	/**
	 * @depends testNegativeConstruction
	 */
	public function testNegativeEncoding(Integer $int)
	{
		$int->read(-1);
		$this->assertEquals('i-1e', $int->encode());
	}

	/**
	 * @depends testBasicConstruction
	 * @expectedException \DSH\Bencode\Exceptions\IntegerException
	 */
	public function testInvalidEncoding(Integer $int)
	{
		$int->read('hello!');
	}
}
