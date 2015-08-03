<?php

use DSH\Bencode\Dictionary;
use DSH\Bencode\ElementList;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;

class DictionaryTestCase extends PHPUnit_Framework_TestCase
{
	private $_dictionary = array(5 => 'test', 'key' => 2);
	private $_encoded = 'di5e4:test3:keyi2ee';
	
	public function testDictionaryCreation()
	{
		$dictionary = new Dictionary();
		$array_dictionary = new Dictionary($this->_dictionary);
		$stream_dictionary = new Dictionary($this->_encoded);
	}
	
	public function testDictionaryEncoding()
	{
		$dictionary = new Dictionary($this->_dictionary);
		
		$this->assertEquals($this->_encoded, $dictionary->encode());
	}
	
	public function testDictionaryWriting()
	{
		$dictionary = new Dictionary($this->_dictionary);
		$new_array = $dictionary->write();
		
		$this->assertEquals($new_array[5], $this->_dictionary[5]);
	}
}
