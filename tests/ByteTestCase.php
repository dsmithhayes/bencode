<?php

use DSH\Bencode\Byte;
use DSH\Bencode\Integer;

class ByteTestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException IntegerException
	 */
	public function testConstructorException()
	{
		$int = new Integer();
	}
}
