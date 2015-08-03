<?php

use DSH\Bencode\Core\Element;
use DSH\Bencode\ElementList;
use DSH\Bencode\Byte;
use DSH\Bencode\Integer;

class ElementListTestCase extends PHPUnit_Framework_TestCase
{
	private $_valid_raw_array = array(23, 'test', 29);
	private $_valid_raw_stream = 'li23e4:testi29ee';
	
	public function testConstruction()
	{
		$empty_element_list = new ElementList();
		$array_element_list = new ElementList($this->_valid_raw_array);
	}
	
	public function testReadInt()
	{
		$element_list = new ElementList($this->_valid_raw_array);
		
		$list = $element_list->dropEncoding($this->_valid_raw_stream);
		
		$int = $element_list->readInt($list);
		$this->assertEquals(23, $int->write());
	}
	
	public function testReadByteSize()
	{
		$element_list = new ElementList();
		$stream = '4:test';
		
		$byte_size = $element_list->readByteSize($stream);
		$this->assertEquals(4, $byte_size);
	}
	
	public function testReadByteRaw()
	{
		$element_list = new ElementList();
		$stream = '4:test';
		
		$byte_raw = $element_list->readByteRaw($stream);
		
		$this->assertEquals('test', $byte_raw);
	}
	
	public function testReadByte()
	{
		$element_list = new ElementList();
		$byte = new Byte('test');
		
		$byte = $element_list->readByte($byte->encode());
		
		$array = $byte->write();
		
		$this->assertArrayHasKey('size', $array);
		$this->assertArrayHasKey('raw', $array);
	}
	
	public function testElementListDecode()
	{
		$element_list = new ElementList($this->_valid_raw_array);
		$byte = new Byte('test');
		$other_byte = new Byte('other');
		$int = new Integer(15);
		
		$encoded_byte = $byte->encode();
		$encoded_int = $int->encode();
		
		$encoded = ElementList::START 
				. $byte->encode()
				. $int->encode()
				. $other_byte->encode()
				. ElementList::END;
		
		$element_list->decode($encoded);
		
		foreach($element_list->write() as $element)
			$this->assertInstanceOf('\\DSH\\Bencode\\Core\\Element', $element);
	}
	
	public function testElementListEncode()
	{
		$element_list = new ElementList($this->_valid_raw_array);
		$this->assertEquals($this->_valid_raw_stream, $element_list->encode());
	}
}
