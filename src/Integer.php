<?php

namespace DSH\Bencode;

use DSH\Bencode\Core\Element;
use DSH\Bencode\Core\Streamable;

class Integer implements Element, Streamable
{
	protected $_buffer;
	
	public function __construct($int)
	{
		
	}
}
