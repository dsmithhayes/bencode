<?php

use DSH\Bencode\ElementList;

class ElementListTestCase extends PHPUnit_Framework_TestCase
{
	public function testEmptyConstruction()
	{
		$list = new ElementList();
		$this->assertEmpty($list->write());
		
		return $list;
	}
	
	public function testBasicConstruction()
	{
		$list = new ElementList(1);
		$buffer = $list->write();
		$this->assertEquals(1, $buffer[0]);
		
		return $list;
	}
	
	public function testArrayConstruction()
	{
		$list = new ElementList(array(0, 1, 'hello'));
		$this->assertNotEmpty($list->write());
		
		return $list;
	}
	
	/**
	 * @depends testBasicConstruction
	 */
	public function testBasicEncoding(ElementList $list)
	{
		$this->assertEquals('li1ee', $list->encode());
	}
	
	/**
	 * @depends testArrayConstruction
	 */
	public function testArrayEncoding(ElementList $list)
	{
		$this->assertEquals('li0ei1e5:helloe', $list->encode());
	}
	
	/**
	 * @depends testEmptyConstruction
	 */
	public function testMixedDecoding(ElementList $list)
	{
		$stream = 'l4:testi35ee';
		$list->decode($stream);
		
		$this->assertEquals($stream, $list->encode());
	}
}
