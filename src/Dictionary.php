<?php namespace Bencode;

use Bencode\Core\Element;
use Bencode\Core\Buffer;
use Bencode\Core\Reader;
use Bencode\Exception\DictionaryException;

class Dictionary extends Reader implements Element, Buffer
{
	private $_buf = ['encoded' => [], 'Element' => []]; 
	
	const START = 'd';
	const END = parent::END;
	
	public function __construct($in = null)
	{
		
	}
	
	public function encode()
	{
		
	}
	
	public function decode($in)
	{
		
	}
	
	public function valid($in)
	{
		
	}
	
	public function write()
	{
		
	}
	
	public function read()
	{
		
	}
}
